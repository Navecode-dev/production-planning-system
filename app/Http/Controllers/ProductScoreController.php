<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Criteria;
use App\Models\ProductScore;
use Illuminate\Http\Request;

class ProductScoreController extends Controller
{
    public function edit(Product $product)
    {
        $criteria = Criteria::where('category', 'product')->get();

        $scores = ProductScore::where('product_id', $product->id)
                              ->get()
                              ->keyBy('criteria_id');

        return view('products.scores', compact('product', 'criteria', 'scores'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->scores as $criteriaId => $value) {
            ProductScore::updateOrCreate(
                ['product_id' => $product->id, 'criteria_id' => $criteriaId],
                ['value' => $value]
            );
        }

        return redirect()->route('products.scores.edit', $product->id)
                         ->with('success', 'Skor produk berhasil diperbarui!');
    }
}