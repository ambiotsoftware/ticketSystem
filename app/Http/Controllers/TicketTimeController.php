<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketTimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\TicketStatus;
use App\Services\NotificationService;

class TicketTimeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function timeControl(Request $request, Ticket $ticket)
    {
        if (Auth::id() !== $ticket->assigned_user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'action' => 'required|string|in:start,pause,resume,stop',
            'comment' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:5120'
        ]);

        $action = $validated['action'];
        $now = now();

        if ($action === 'start' || $action === 'resume') {
            $ticket->estado = TicketStatus::TRACKING;
            TicketTimeEntry::create([
                'ticket_id' => $ticket->id,
                'technician_id' => Auth::id(),
                'started_at' => $now,
                'status' => 'started',
                'comment' => $validated['comment'],
            ]);
            $this->notificationService->notifyTicketStarted($ticket, Auth::user());
        } else { // pause o stop
            $lastEntry = TicketTimeEntry::where('ticket_id', $ticket->id)
                ->where('technician_id', Auth::id())
                ->where('status', 'started')
                ->latest('started_at')
                ->first();

            if ($lastEntry) {
                $lastEntry->ended_at = $now;
                $lastEntry->duration_minutes = $lastEntry->started_at->diffInMinutes($now);
                $lastEntry->comment = $validated['comment'];
                
                if ($action === 'pause') {
                    $ticket->estado = TicketStatus::PAUSED;
                    $lastEntry->status = 'paused';
                    $lastEntry->save();
                    $this->notificationService->notifyTicketPaused($ticket, Auth::user(), $validated['comment']);
                } else { // stop
                    $ticket->estado = TicketStatus::RESOLVED;
                    $lastEntry->status = 'stopped';
                    $lastEntry->save();
                    $this->notificationService->notifyTicketResolved($ticket, Auth::user(), $validated['comment']);
                }
                
            } else {
                return response()->json(['message' => 'No se encontró un registro de tiempo activo para este ticket. No se puede pausar o detener.'], 422);
            }
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket_attachments/' . $ticket->id, 'public');
                $ticket->attachments()->create([
                    'uploaded_by' => Auth::id(),
                    'file_path' => $path,
                    'filename' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                ]);
            }
        }

        $ticket->save();

        return response()->json(['message' => 'Acción registrada con éxito.']);
    }
}
