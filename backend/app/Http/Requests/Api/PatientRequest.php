<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $patientId = $this->route('patient')?->id;

        return [
            'code' => [
                'required', 'string', 'max:20',
                Rule::unique('patients', 'code')
                    ->where(fn ($q) => $q->where('user_id', auth()->id()))
                    ->ignore($patientId),
            ],
            'full_name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:0', 'max:120'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'height_cm' => ['required', 'numeric', 'min:50', 'max:250'],
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:500'],
            'asa_class' => ['required', Rule::in(['I', 'II', 'III', 'IV', 'V'])],
            'is_pregnant' => ['sometimes', 'boolean'],
            'respiratory_condition' => ['required', Rule::in(['routine', 'asthma_copd', 'ards', 'co2_endoscopy'])],
            'surgery_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
