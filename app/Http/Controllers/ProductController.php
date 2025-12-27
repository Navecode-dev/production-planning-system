<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('products.edit', $row->id) . '" class="edit btn btn-primary btn-sm">Ubah</a>';
                    $btn .= ' <form action="' . route('products.destroy', $row->id) . '" method="POST" style="display:inline">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>';
                    return $btn;
                })
                ->addColumn('input_score', function($row){
                    return '<a href="'.route('products.scores.edit', $row->id).'" class="btn btn-info btn-sm">Input Skor</a>';
                })
                ->rawColumns(['action', 'input_score'])
                ->make(true);
        }
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'brand' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'stock' => 'required|integer',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'brand' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'stock' => 'required|integer',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}