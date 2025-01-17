<?php

namespace App\Http\Requests\Product;

use App\Enum\Product\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
    * @return void
    */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'author_id' => $this->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author_id' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'regex:/^\d{1,12}(\.\d{1,2})?$/'],
            'unit' => ['required', Rule::in(Unit::cases())],
            'quantity' => ['required', 'integer'],
        ];
    }
}
