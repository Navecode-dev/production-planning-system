<?php

namespace App\Http\Controllers;

use App\Models\EoqCalculation;
use App\Models\RawMaterial;
use App\Models\SettingCost;
use Illuminate\Http\Request;

class EoqController extends Controller
{
    public function index()
    {
        $rawMaterials = RawMaterial::all();
        return view('eoq.index', compact('rawMaterials'));
    }

    public function showCalculator($id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        $setupCosts = SettingCost::where('group', 'setup_cost')->get();
        $totalSetupCost = $setupCosts->sum('value');
        $holdingCost = $rawMaterial->storage_cost;

        return view('eoq.calculator', compact('rawMaterial', 'totalSetupCost', 'holdingCost'));
    }

    public function calculate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'demand' => 'required|numeric|min:1',
            'lead_time' => 'required|numeric|min:0',
            'working_days' => 'required|numeric|min:1|integer',
            'max_daily_demand' => 'required|numeric|min:0',
        ]);

        $rawMaterial = RawMaterial::findOrFail($id);
        $demand = (float) $validatedData['demand'];
        $leadTime = (float) $validatedData['lead_time'];
        $workingDays = (int) $validatedData['working_days'];
        $maxDailyDemand = (float) $validatedData['max_daily_demand'];

        $setupCosts = SettingCost::where('group', 'setup_cost')->get();
        $orderingCost = $setupCosts->sum('value');
        $holdingCost = $rawMaterial->storage_cost;

        if ($holdingCost <= 0) {
            return redirect()->back()->with('error', 'Biaya Penyimpanan (H) harus lebih besar dari nol.')->withInput();
        }

        $eoq = sqrt((2 * $demand * $orderingCost) / $holdingCost);
        $optimalFrequency = ($eoq > 0) ? $demand / $eoq : 0;
        $totalCost = (($demand / $eoq) * $orderingCost) + (($eoq / 2) * $holdingCost);

        $dailyDemand = $demand / $workingDays;

        if ($maxDailyDemand < $dailyDemand) {
             return redirect()->back()->with('error', 'Permintaan Harian Maksimum tidak boleh lebih kecil dari Permintaan Harian Rata-rata.')->withInput();
        }

        $safetyStock = round(($maxDailyDemand - $dailyDemand) * $leadTime);
        $rop = round(($dailyDemand * $leadTime) + $safetyStock);

        EoqCalculation::create([
            'raw_material_id' => $rawMaterial->id,
            'annual_demand' => $demand,
            'ordering_cost' => $orderingCost,
            'holding_cost' => $holdingCost,
            'eoq_value' => $eoq,
            'total_cost' => $totalCost,
            'optimal_frequency' => $optimalFrequency,
            'lead_time' => $leadTime,
            'working_days' => $workingDays,
            'max_daily_demand' => $maxDailyDemand,
            'daily_demand' => $dailyDemand,
            'safety_stock' => $safetyStock,
            'rop_value' => $rop,
        ]);

        return view('eoq.results', [
            'rawMaterial' => $rawMaterial,
            'demand' => $demand,
            'orderingCost' => $orderingCost,
            'holdingCost' => $holdingCost,
            'eoq' => $eoq,
            'optimalFrequency' => $optimalFrequency,
            'totalCost' => $totalCost,
            'dailyDemand' => $dailyDemand,
            'safetyStock' => $safetyStock,
            'rop' => $rop,
        ]);
    }

    public function history(Request $request)
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

        $calculations = $query->paginate(20)->withQueryString();

        $rawMaterials = RawMaterial::orderBy('name')->get();

        return view('eoq.history', compact('calculations', 'startDate', 'endDate', 'rawMaterials', 'rawMaterialId'));
    }
}