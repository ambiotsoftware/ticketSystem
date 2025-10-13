<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use App\Services\TicketService;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;
use App\Enums\TicketStatus;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
        $this->authorizeResource(Ticket::class, 'ticket');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = Ticket::with(['category', 'assignedUser', 'client']);
        
        // Filtrar según el rol del usuario
        if ($user->role === UserRole::CLIENT) {
            $query->where('client_id', $user->id);
        } elseif ($user->role === UserRole::TECHNICIAN) {
            $query->where('assigned_user_id', $user->id);
        }
        // Los administradores ven todos los tickets
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $categories = Category::where('activa', true)->get();
        
        // Solo los administradores ven la opción de asignar directamente
        $users = [];
        if ($user->role === UserRole::ADMIN) {
            $users = User::where('role', UserRole::TECHNICIAN)->where('active', true)->get();
        }
        
        return view('tickets.create', compact('categories', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->ticketService->createTicket($request->validated());

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket creado exitosamente. Se han enviado las notificaciones correspondientes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['category', 'assignedUser', 'client']);
        
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $categories = Category::where('activa', true)->get();
        $users = User::all();
        
        return view('tickets.edit', compact('ticket', 'categories', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();

        // Forzar la conversión de Enum a valor de string antes de guardar.
        if (isset($validated['estado']) && $validated['estado'] instanceof \App\Enums\TicketStatus) {
            $validated['estado'] = $validated['estado']->value;
        }

        if (isset($validated['prioridad']) && $validated['prioridad'] instanceof \App\Enums\TicketPriority) {
            $validated['prioridad'] = $validated['prioridad']->value;
        }

        // Usar fill() y save() para asegurar que el ciclo de vida del modelo se respete.
        $ticket->fill($validated);
        $ticket->save();

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket actualizado exitosamente.');
    }
     /* Asignar rápidamente un ticket a un técnico (solo admin)
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $data = $request->validate([
            'assigned_user_id' => 'required|exists:users,id'
        ]);

        $this->ticketService->assignTicket($ticket, $data['assigned_user_id']);

        return redirect()->back()->with('success', 'Ticket asignado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function reassign(Request $request, Ticket $ticket)
    {
        // Autorización: solo el técnico asignado puede reasignar
        if (Auth::id() !== $ticket->assigned_user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate(['reassign_reason' => 'required|string|min:10']);

        $this->ticketService->reassignTicket($ticket, $validated['reassign_reason']);

        return response()->json(['message' => 'Ticket devuelto a la cola de asignación.']);
    }

    
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket eliminado exitosamente.');
    }
}
