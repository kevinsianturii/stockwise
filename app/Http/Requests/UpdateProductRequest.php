<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'sku'            => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'name'           => ['required', 'string', 'max:200'],
            'category_id'    => ['required', 'exists:categories,id'],
            'supplier_id'    => ['nullable', 'exists:suppliers,id'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'minimum_stock'  => ['required', 'integer', 'min:0'],
            'unit'           => ['required', 'string', 'max:30'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required'            => 'SKU wajib diisi.',
            'sku.unique'              => 'SKU sudah digunakan oleh produk lain.',
            'name.required'           => 'Nama produk wajib diisi.',
            'category_id.required'    => 'Kategori wajib dipilih.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'purchase_price.min'      => 'Harga beli tidak boleh negatif.',
            'selling_price.required'  => 'Harga jual wajib diisi.',
            'selling_price.min'       => 'Harga jual tidak boleh negatif.',
            'minimum_stock.required'  => 'Stok minimum wajib diisi.',
            'minimum_stock.min'       => 'Stok minimum tidak boleh negatif.',
            'unit.required'           => 'Satuan wajib diisi.',
        ];
    }
}
