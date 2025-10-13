<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\TicketPriority;
use Illuminate\Validation\Rules\Enum;
use App\Enums\UserRole;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var Ticket $ticket */
        $ticket = $this->route('ticket');
        $user = Auth::user();

        // Permitir si es admin, el técnico asignado o el cliente que lo creó.
        // Solo el admin o el cliente que lo creó pueden editar el formulario.
        return $user->role === UserRole::ADMIN || $user->id === $ticket->client_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'prioridad' => ['required', new Enum(TicketPriority::class)],
            'category_id' => 'nullable|exists:categories,id',
            'fecha_limite' => 'nullable|date'
        ];

        if (Auth::user()->role !== UserRole::CLIENT) {
            $rules['estado'] = ['required', new Enum(TicketStatus::class)];
            $rules['assigned_user_id'] = 'nullable|exists:users,id';
        }

        return $rules;
    }
}
