<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentUpdateRequest extends FormRequest
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
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:agents,email,' . $this->route('agent')->id,
            'phone_number' => 'sometimes|required|string',
            'alternate_phone_number' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'sub_location_id' => 'sometimes|required|exists:sub_locations,id',
        ];
    }
}
