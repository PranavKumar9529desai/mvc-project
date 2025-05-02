<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
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
            'farm_id'      => 'required|exists:farms,id',
            'batch_number' => 'required|string|max:255',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date',
            'wool_type'    => 'nullable|string|max:255',
            'weight_kg'    => 'nullable|numeric',
            'status'       => 'nullable|string|max:255',
            'arrival_date' => 'nullable|date',
            'notes'        => 'nullable|string',
        ];
    }
}
