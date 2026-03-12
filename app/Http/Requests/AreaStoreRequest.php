<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AreaStoreRequest extends FormRequest
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
            'name' => 'required|max:255|regex:/^[a-zA-Z0-9 ]+$/',
            // 'name' => 'required|max:255|regex:/^[a-zA-Z0-9 ]+$/|unique:sqlsrv.Ice.UD103A,Character01',
            'code' => 'required|max:50|regex:/^[a-zA-Z0-9 ]+$/|unique:sqlsrv.Ice.UD103A,ShortChar01',
            'region_uuid' => 'required|uuid',
        ];
    }
}
