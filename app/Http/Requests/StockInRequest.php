<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'  => ['required', 'exists:products,id'],
            'quantity'    => ['required', 'integer', 'min:1'],
            'unit_cost'   => ['nullable', 'numeric', 'min:0'],
            'notes'       => ['nullable', 'string', 'max:500'],
            'occurred_at' => ['nullable', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'  => 'Produk wajib dipilih.',
            'product_id.exists'    => 'Produk tidak ditemukan.',
            'quantity.required'    => 'Jumlah wajib diisi.',
            'quantity.integer'     => 'Jumlah harus berupa bilangan bulat.',
            'quantity.min'         => 'Jumlah harus lebih dari 0.',
            'unit_cost.numeric'    => 'Harga satuan harus berupa angka.',
            'unit_cost.min'        => 'Harga satuan tidak boleh negatif.',
            'occurred_at.date'     => 'Format tanggal tidak valid.',
            'occurred_at.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
        ];
    }
}
