<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BenefitTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:benefit_types,name,' . $this->id,
        ];
    }
}