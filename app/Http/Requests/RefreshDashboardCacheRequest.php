<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefreshDashboardCacheRequest extends FormRequest
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
            'sync' => 'sometimes|boolean',
            'force' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'sync.boolean' => 'El parámetro sync debe ser verdadero o falso',
            'force.boolean' => 'El parámetro force debe ser verdadero o falso',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('sync')) {
            $this->merge([
                'sync' => filter_var($this->sync, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        if ($this->has('force')) {
            $this->merge([
                'force' => filter_var($this->force, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}
