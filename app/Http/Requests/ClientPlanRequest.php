<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ClientPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'active' => ['boolean'],
            'custom_plan_cost' => ['nullable', 'numeric'],
            'custom_extra_hour_rate' => ['nullable', 'numeric'],
        ];
    }
}
