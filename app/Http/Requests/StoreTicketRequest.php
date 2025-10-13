<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\TicketPriority;
use Illuminate\Validation\Rules\Enum;
use App\Enums\UserRole;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Solo clientes y administradores pueden crear tickets
        $user = Auth::user();
        return in_array($user->role, [UserRole::CLIENT, UserRole::ADMIN]);
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
            'fecha_limite' => 'nullable|date|after:now',
        ];

        if (Auth::user()->role !== UserRole::CLIENT) {
            $rules['assigned_user_id'] = 'nullable|exists:users,id';
            $rules['client_id'] = 'nullable|exists:users,id'; // Para que los admins creen tickets para otros
        }

        return $rules;
    }
}
