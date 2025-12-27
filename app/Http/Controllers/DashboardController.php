<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Store;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRawMaterials = RawMaterial::count();
        $totalProducts = Product::count();
        $totalStores = Store::count();
        $totalProductCriteria = Criteria::where('category', 'product')->count();
        $totalStoreCriteria = Criteria::where('category', 'store')->count();

        return view('dashboard', compact(
            'totalRawMaterials',
            'totalProducts',
            'totalStores',
            'totalProductCriteria',
            'totalStoreCriteria'
        ));
    }
}
