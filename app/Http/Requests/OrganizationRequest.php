<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'id_type' => 'required|exists:types,id',
            'id_sector' => 'required|exists:sectors,id',
            'size' => 'required|in:Micro (1 a 10 trabajadores),Pequeña (11 a 50 trabajadores),Mediana (51 a 100 trabajadores),Grande (Más de 100 trabajadores)',
            'id_cluster' => 'required|exists:clusters,id',
            'street' => 'required|string|max:255',
            'external_number' => 'required|string|max:255',
            'internal_number' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10|regex:/^\d{5}(-\d{4})?$/',
            'id_state' => 'required|exists:states,id',
            'id_municipality' => 'required|exists:municipalities,id',
            'city' => 'nullable|string|max:255',
            'google_maps' => 'nullable|string|max:255',
            'scope' => 'required|in:Municipal,Federal,Estatal,Internacional',
        ];
    }
}
