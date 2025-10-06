<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DualProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_institution' => ['required', 'exists:institutions,id'],
            'has_report' => ['required', 'boolean'],
            'number_student' => ['required_if:has_report,1', 'integer', 'min:1', 'max:16'],
            'name_report' => ['required_if:has_report,1', 'string', 'max:255'],
            'period_start' => ['required_if:has_report,1', 'date'],
            'period_end' => ['required_if:has_report,1', 'date', 'after_or_equal:period_start'],
            'amount' => ['required_if:has_report,1', 'numeric', 'min:0'],
            'id_dual_area' => ['required_if:has_report,1', 'exists:dual_areas,id'],
            'dual_type_id' => ['required_if:has_report,1', 'exists:dual_types,id'],
            'status_document' => ['required_if:has_report,1', 'exists:document_statuses,id'],
            'economic_support' => ['required_if:has_report,1', 'exists:economic_supports,id'],
            'id_organization' => ['required_if:has_report,1', 'exists:organizations,id'],
            'is_concluded' => ['required_if:has_report,1', 'boolean'],
            'is_hired' => ['required_if:has_report,1', 'boolean'],
            'qualification' => ['nullable', 'integer', 'min:0', 'max:100'],
            'max_qualification' => ['required_if:has_report,1', 'in:10,100'],
            'micro_credentials' => ['nullable', 'array'],
            'micro_credentials.*' => ['exists:micro_credentials,id'],
            'advisor' => ['required_if:has_report,1', 'in:interno,externo'],
            'students' => ['required_if:has_report,1', 'array', 'min:1'],
            'students.*.control_number' => ['required', 'string', 'max:50'],
            'students.*.name_student' => ['required', 'string', 'max:255'],
            'students.*.lastname' => ['required', 'string', 'max:255'],
            'students.*.gender' => ['required', 'in:Masculino,Femenino,Otro'],
            'students.*.semester' => ['required', 'integer', 'min:1', 'max:16'],
            'students.*.id_career' => ['required', 'exists:careers,id'],
            'students.*.id_specialty' => ['nullable', 'exists:specialties,id'],
            'students.*.id_institution' => ['required', 'exists:institutions,id'],
        ];
    }
}
