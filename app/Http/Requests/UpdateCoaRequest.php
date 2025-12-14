<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCoaRequest extends FormRequest
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
        $coa = $this->route('coa');

        return [
            'header_akun' => ['required', 'string'],
            'kode_akun' => [
                'required',
                'string',
                'max:5',
                Rule::unique('coa', 'kode_akun')->ignore($coa?->id),
            ],
            'nama_akun' => ['required', 'string'],
        ];
    }
}
