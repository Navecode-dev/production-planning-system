<?php

namespace App\Http\Controllers;

use App\Models\SettingCost;
use Illuminate\Http\Request;

class SettingCostController extends Controller
{
    public function index()
    {
        $setupCosts = SettingCost::where('group', 'setup_cost')->get();
        return view('setting_costs.index', compact('setupCosts'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.value' => 'required|numeric|min:0',
            'settings.*.key' => 'required|string|exists:setting_costs,key',
        ]);

        foreach ($validated['settings'] as $settingData) {
            $setting = SettingCost::where('key', $settingData['key'])->first();
            if ($setting) {
                $setting->update(['value' => $settingData['value']]);
            }
        }

        return redirect()->route('setting-costs.index')
                         ->with('success', 'Pengaturan biaya berhasil diperbarui!');
    }
}
