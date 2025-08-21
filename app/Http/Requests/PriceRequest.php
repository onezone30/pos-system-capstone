<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
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
            'price' => ['array'],
            'price.*' => ['nullable', 'numeric'],
            'quantity_stock' => ['array'],
            'quantity_stock.*' => ['nullable', 'integer'],
            'size' => ['array'],
            'size.*' => ['nullable', 'string'],
        ];
    }
}
