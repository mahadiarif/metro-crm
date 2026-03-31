<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'sometimes|required|string|max:255',
            'client_name' => 'sometimes|required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|max:255',
            'existing_provider' => 'nullable|string|max:255',
            'current_usage' => 'nullable|string',
            'service_id' => 'sometimes|required|exists:services,id',
            'service_package_id' => 'nullable|exists:service_packages,id',
            'status' => 'sometimes|required|string|in:new,contacted,interested,closed,lost',
            'assigned_user' => 'nullable|exists:users,id',
            'stage_id' => 'sometimes|required|exists:pipeline_stages,id',
            'lead_date' => 'nullable|date',
            'zone' => 'nullable|string|max:255',
        ];
    }
}
