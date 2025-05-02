<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StageRecordRequest extends FormRequest
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
            'batch_id' => 'required|exists:batches,id',
            'stage'    => 'required|string|in:cleaning,sorting,scouring,drying,quality_check,packaging',
            'notes'    => 'nullable|string',
            'completion_date' => 'nullable|date',
        ];
    }
}
