<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Storemember_results_at_stationRequest extends FormRequest
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
            'teamAtStationId' => 'integer',
            'teamMemberId' => 'integer',
            'result' => 'nullable|integer',
            'resultTime' => 'nullable|integer',
        ];
    }
}
