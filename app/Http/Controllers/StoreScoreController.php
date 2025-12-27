<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Criteria;
use App\Models\StoreScore;
use Illuminate\Http\Request;

class StoreScoreController extends Controller
{
    public function edit(Store $store)
    {
        $criteria = Criteria::where('category', 'store')->get();

        $scores = StoreScore::where('store_id', $store->id)
                              ->get()
                              ->keyBy('criteria_id');

        return view('stores.scores', compact('store', 'criteria', 'scores'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:0',
        ]);

        foreach ($request->scores as $criteriaId => $value) {
            StoreScore::updateOrCreate(
                ['store_id' => $store->id, 'criteria_id' => $criteriaId],
                ['value' => $value]
            );
        }

        return redirect()->route('stores.scores.edit', $store->id)
                         ->with('success', 'Skor toko berhasil diperbarui!');
    }
}