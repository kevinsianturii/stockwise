<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku'            => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name'           => ['required', 'string', 'max:200'],
            'category_id'    => ['required', 'exists:categories,id'],
            'supplier_id'    => ['nullable', 'exists:suppliers,id'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'minimum_stock'  => ['required', 'integer', 'min:0'],
            'unit'           => ['required', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required'            => 'SKU wajib diisi.',
            'sku.unique'              => 'SKU sudah digunakan oleh produk lain.',
            'sku.max'                 => 'SKU maksimal 50 karakter.',
            'name.required'           => 'Nama produk wajib diisi.',
            'name.max'                => 'Nama produk maksimal 200 karakter.',
            'category_id.required'    => 'Kategori wajib dipilih.',
            'category_id.exists'      => 'Kategori yang dipilih tidak valid.',
            'supplier_id.exists'      => 'Supplier yang dipilih tidak valid.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'purchase_price.numeric'  => 'Harga beli harus berupa angka.',
            'purchase_price.min'      => 'Harga beli tidak boleh negatif.',
            'selling_price.required'  => 'Harga jual wajib diisi.',
            'selling_price.numeric'   => 'Harga jual harus berupa angka.',
            'selling_price.min'       => 'Harga jual tidak boleh negatif.',
            'minimum_stock.required'  => 'Stok minimum wajib diisi.',
            'minimum_stock.integer'   => 'Stok minimum harus berupa bilangan bulat.',
            'minimum_stock.min'       => 'Stok minimum tidak boleh negatif.',
            'unit.required'           => 'Satuan wajib diisi.',
        ];
    }
}
