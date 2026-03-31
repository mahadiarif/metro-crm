<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'existing_provider' => 'nullable|string|max:255',
            'current_usage' => 'nullable|string',
            'service_id' => 'required|exists:services,id',
            'service_package_id' => 'nullable|exists:service_packages,id',
            'status' => 'required|string|in:new,contacted,interested,closed,lost',
            'assigned_user' => 'nullable|exists:users,id',
            'stage_id' => 'nullable|exists:pipeline_stages,id',
            'lead_date' => 'nullable|date',
            'zone' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is mandatory.',
            'service_id.exists' => 'The selected service is invalid.',
            'status.in' => 'Please select a valid status.',
        ];
    }
}
