<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VehicleUpdateRequest extends FormRequest
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
            'status' => 'nullable|in:active,inactive,pending',
            'status' => 'required|in:active,inactive,pending',
            'type' => 'required|string|max:255|in:kuning,hitam,putih',
            'vehicle_type_uuid' => 'required|uuid',
            'description' => 'nullable|string',
            'transporter_uuid' => 'required|uuid',
            'multi_transporter_uuid' => 'array',
            'multi_transporter_uuid.*' => 'uuid',
            'ownership' => 'required|string',
        ];
    }
}
