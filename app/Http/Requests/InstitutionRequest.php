<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class InstitutionRequest extends FormRequest
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
            'street' => 'required|string|max:255',
            'external_number' => 'required|string|max:255',
            'internal_number' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10|regex:/^\d{5}(-\d{4})?$/',
            'id_state' => 'required|exists:states,id',
            'id_municipality' => 'required|exists:municipalities,id',
            'city' => 'nullable|string|max:255',
            'google_maps' => 'nullable|string|max:255',
            'type' => 'required|in:Pública,Privada',
            'id_subsystem' => 'required|exists:subsystems,id',
            'id_academic_period' => 'required|exists:academic_periods,id',
            'image' => 'nullable|string'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $stateId = $this->input('id_state');
            $municipalityId = $this->input('id_municipality');

            if ($stateId && $municipalityId) {
                $exists = DB::table('municipalities')
                    ->where('id', $municipalityId)
                    ->where('id_state', $stateId)
                    ->exists();

                if (! $exists) {
                    $validator->errors()->add('id_municipality', 'El municipio no pertenece al estado seleccionado.');
                }
            }
        });
    }
}
