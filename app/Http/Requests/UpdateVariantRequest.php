<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product_Variant;

class UpdateVariantRequest extends FormRequest
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
        $variantId = $this->route('product_variant');
        return [
            'attributes' => 'string|required',
            'price' => 'required|numeric',
            'sku' => ['required','string', Rule::unique('product__variants')->ignore($variantId)],
            'stock' => 'required|integer|min:0',
        ];
    }
}
