<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporJualanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stock_allocation_id' => 'required|exists:stock_allocations,id',
            'qty_sold'            => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'qty_sold.min'                => 'Jumlah terjual tidak boleh negatif.',
            'stock_allocation_id.exists'  => 'Data alokasi stok tidak ditemukan.',
            'qty_sold.required'           => 'Jumlah terjual wajib diisi.',
        ];
    }
}