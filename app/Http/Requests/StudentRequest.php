<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'control_number' => 'required|string|max:50|unique:students,control_number,' . $this->id,
            'name'           => 'required|string|max:255',
            'lastname'       => 'required|string|max:255',
            'gender'         => 'required|in:Masculino,Femenino,Otro',
            'semester'       => 'required|integer|min:1|max:12',
            'id_institution' => 'required|exists:institutions,id',
            'id_specialty'   => 'nullable|exists:specialties,id',
            'id_career'      => 'nullable|exists:careers,id',
            'id_dual_project'=> 'nullable|exists:dual_projects,id',
        ];
    }
}
