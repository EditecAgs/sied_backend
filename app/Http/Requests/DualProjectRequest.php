<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DualProjectRequest extends FormRequest
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
            'id_institution' => ['required', 'exists:institutions,id'],
            'has_report' => ['required', 'boolean'],
            'name_report' => ['required_if:has_report,1', 'string', 'max:255'],
            'period_start' => ['required_if:has_report,1', 'date'],
            'period_end' => ['required_if:has_report,1', 'date', 'after_or_equal:period_start'],
            'amount' => ['required_if:has_report,1', 'numeric', 'min:0'],
            'id_dual_area' => ['required_if:has_report,1', 'exists:dual_areas,id'],
            'status_document' => ['required_if:has_report,1', 'exists:document_statuses,id'],
            'economic_support' => ['required_if:has_report,1', 'exists:economic_supports,id'],
            'id_organization' => ['required_if:has_report,1', 'exists:organizations,id'],
            'control_number' => ['required_if:has_report,1'],
            'name_student' => ['required_if:has_report,1', 'string', 'max:255'],
            'number_student' => ['required_if:has_report,1', 'string', 'max:50'],
            'lastname' => ['required_if:has_report,1', 'string', 'max:255'],
            'gender' => ['required_if:has_report,1', 'in:Masculino,Femenino,Otro'],
            'semester' => ['required_if:has_report,1', 'integer', 'min:1', 'max:16'],
            'id_career' => ['required_if:has_report,1', 'exists:careers,id'],
            'id_specialty' => ['required_if:has_report,1', 'exists:specialties,id'],
            'is_concluded' => ['required_if:has_report,1', 'boolean'],
            'is_hired' => ['required_if:has_report,1', 'boolean'],
            'qualification' => ['nullable', 'integer', 'min:0', 'max:100'],
            'advisor' => ['required_if:has_report,1', 'in:interno,externo'],
            'dual_type_id' => ['required_if:has_report,1', 'exists:dual_types,id'],
        ];
    }
}
