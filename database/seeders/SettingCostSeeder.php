<?php

namespace Database\Seeders;

use App\Models\SettingCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'setup_cost_hr', 'label' => 'Upah SDM', 'value' => '127514', 'type' => 'number', 'group' => 'setup_cost'
            ],
            [
                'key' => 'setup_cost_transport', 'label' => 'Biaya Transportasi', 'value' => '1125000', 'type' => 'number', 'group' => 'setup_cost'
            ],
            [
                'key' => 'setup_cost_admin', 'label' => 'Biaya Administrasi', 'value' => '124000', 'type' => 'number', 'group' => 'setup_cost'
            ],
            [
                'key' => 'setup_cost_loading', 'label' => 'Biaya Bongkar Muat', 'value' => '110000', 'type' => 'number', 'group' => 'setup_cost'
            ],
        ];

        foreach ($settings as $setting) {
            SettingCost::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
