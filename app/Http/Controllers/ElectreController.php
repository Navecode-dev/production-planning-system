<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Criteria;
use App\Models\Product;
use App\Models\ElectreProductCalculation;
use App\Models\ElectreStoreCalculation;
use App\Services\ElectreService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ElectreController extends Controller
{
    protected $electreService;

    public function __construct(ElectreService $electreService)
    {
        $this->electreService = $electreService;
    }
    public function productIndex()
    {
        $products = Product::with('scores.criteria')->get();
        $criteria = Criteria::where('category', 'product')->orderBy('id')->get();

        return view("electre.product_index", compact("products", "criteria"));
    }

    public function productCalculate(Request $request)
    {
        $results = $this->electreService->calculate(Product::class, 'product');

        if (isset($results['ranking']) && !empty($results['ranking'])) {
            ElectreProductCalculation::create([
                'ranking' => $results['ranking'],
            ]);
        }

        if (is_null($results)) {
            return redirect()->route('electre.product.index')->with("error", "Silakan tambahkan produk dan kriteria terkait produk terlebih dahulu.");
        }

        $results['products'] = $results['items'];
        $results['ranking'] = $results['ranking'];
        unset($results['items']);

        return view("electre.product_results", $results);
    }
    
    public function productHistory(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = ElectreProductCalculation::latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $calculations = $query->paginate(10)->withQueryString();

        return view('electre.product_history', compact('calculations', 'startDate', 'endDate'));
    }

    public function storeIndex()
    {
        $stores = Store::with('scores.criteria')->get();
        $criteria = Criteria::where('category', 'store')->orderBy('id')->get();

        return view("electre.store_index", compact("stores", "criteria"));
    }

    public function storeHistory(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = ElectreStoreCalculation::latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $calculations = $query->paginate(10)->withQueryString();

        return view('electre.store_history', compact('calculations', 'startDate', 'endDate'));
    }

    public function storeCalculate(Request $request)
    {
        $results = $this->electreService->calculate(Store::class, 'store');

        if (is_null($results)) {
            return redirect()->route('electre.store.index')->with("error", "Silakan tambahkan toko dan kriteria terkait toko terlebih dahulu.");
        }

        if (isset($results['ranking']) && !empty($results['ranking'])) {
            ElectreStoreCalculation::create([
                'ranking' => $results['ranking'],
            ]);
        }

        $results['stores'] = $results['items'];
        unset($results['items']);

        return view("electre.store_results", $results);
    }
}
