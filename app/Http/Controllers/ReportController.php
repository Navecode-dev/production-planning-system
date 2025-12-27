<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\Product;
use App\Models\Store;
use App\Models\ElectreProductCalculation;
use App\Models\ElectreStoreCalculation;
use App\Models\EoqCalculation;
use App\Services\ElectreService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $electreService;

    public function __construct(ElectreService $electreService)
    {
        $this->electreService = $electreService;
    }

    public function eoqHistoryPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $rawMaterialId = $request->input('raw_material_id');

        $query = EoqCalculation::with('rawMaterial')->latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($rawMaterialId) {
            $query->where('raw_material_id', $rawMaterialId);
        }

        $calculations = $query->get();

        $rawMaterial = $rawMaterialId ? RawMaterial::find($rawMaterialId) : null;

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'raw_material_name' => $rawMaterial ? $rawMaterial->name : 'Semua',
        ];

        $pdf = Pdf::loadView("reports.eoq_history_pdf", compact("calculations", "filters"));
        return $pdf->download("laporan-riwayat-eoq-terfilter.pdf");
    }

    public function electreProductHistoryPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = ElectreProductCalculation::latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $calculations = $query->get();

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $pdf = Pdf::loadView("reports.electre_product_history_pdf", compact("calculations", "filters"));
        return $pdf->download("laporan-riwayat-electre-produk.pdf");
    }

    public function electreStoreHistoryPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = ElectreStoreCalculation::latest();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $calculations = $query->get();

        $filters = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];

        $pdf = Pdf::loadView("reports.electre_store_history_pdf", compact("calculations", "filters"));
        return $pdf->download("laporan-riwayat-electre-toko.pdf");
    }

    
    public function jadwalProduksi(Request $request)
{

    $latestProductCalculation = ElectreProductCalculation::latest()->first();
    
    $latestStoreCalculation = ElectreStoreCalculation::latest()->first();
    
    $productRanking = [];
    $storeRanking = [];
    
    if ($latestProductCalculation) {
        $ranking = $latestProductCalculation->ranking;
        arsort($ranking); // Sort descending by score
        $rank = 1;
        foreach ($ranking as $productName => $score) {
            $productRanking[] = [
                'rank' => $rank++,
                'name' => $productName,
                'score' => $score
            ];
        }
    }
    
    if ($latestStoreCalculation) {
        $ranking = $latestStoreCalculation->ranking;
        arsort($ranking); // Sort descending by score
        $rank = 1;
        foreach ($ranking as $storeName => $score) {
            $storeRanking[] = [
                'rank' => $rank++,
                'name' => $storeName,
                'score' => $score
            ];
        }
    }
    
    return view('reports.jadwal_produksi', compact(
        'productRanking', 
        'storeRanking',
        'latestProductCalculation',
        'latestStoreCalculation'
    ));
}

    public function jadwalProduksiPdf(Request $request)
{
    $latestProductCalculation = ElectreProductCalculation::latest()->first();
    $latestStoreCalculation = ElectreStoreCalculation::latest()->first();
    
    $productRanking = [];
    $storeRanking = [];
    
    if ($latestProductCalculation) {
        $ranking = $latestProductCalculation->ranking;
        arsort($ranking);
        $rank = 1;
        foreach ($ranking as $productName => $score) {
            $productRanking[] = [
                'rank' => $rank++,
                'name' => $productName,
                'score' => $score
            ];
        }
    }
    
    if ($latestStoreCalculation) {
        $ranking = $latestStoreCalculation->ranking;
        arsort($ranking);
        $rank = 1;
        foreach ($ranking as $storeName => $score) {
            $storeRanking[] = [
                'rank' => $rank++,
                'name' => $storeName,
                'score' => $score
            ];
        }
    }
    
    $pdf = Pdf::loadView("reports.jadwal_produksi_pdf", compact(
        'productRanking', 
        'storeRanking',
        'latestProductCalculation',
        'latestStoreCalculation'
    ));
    
    return $pdf->download("jadwal-produksi-" . date('Y-m-d') . ".pdf");
}
    
}
