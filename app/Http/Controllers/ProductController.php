<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255', 'unique:products,name'],
            'price' => ['required', 'integer', 'min:0'],
        ]);

        Product::create($validated);

        return redirect('/admin/products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'  => ['sometimes', 'string', 'max:255', Rule::unique('products', 'name')->ignore($product->id)],
            'price' => ['sometimes', 'integer', 'min:0'],
        ]);

        $product->update($validated);

        return redirect('/admin/products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->stockAllocations()->exists()) {
            return back()->with('error', 'Produk tidak bisa dihapus karena masih ada data stok terkait.');
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}