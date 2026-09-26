<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentilatorPresetSeeder extends Seeder
{
    public function run(): void
    {
        $presets = [
            // Thường quy
            ['clinical_scenario' => 'routine', 'parameter' => 'Vt', 'value_min' => 6, 'value_max' => 7, 'default_value' => 6.5, 'value_display' => '6-7 mL/kg PBW', 'unit' => 'mL', 'sort_order' => 1, 'warning' => 'Vt tính theo PBW'],
            ['clinical_scenario' => 'routine', 'parameter' => 'RR', 'value_min' => 12, 'value_max' => 16, 'default_value' => 14, 'value_display' => '14', 'unit' => 'lần/phút', 'sort_order' => 2],
            ['clinical_scenario' => 'routine', 'parameter' => 'PEEP', 'value_min' => 5, 'value_max' => 5, 'default_value' => 5, 'value_display' => '5', 'unit' => 'cmH2O', 'sort_order' => 3],
            ['clinical_scenario' => 'routine', 'parameter' => 'FiO2', 'value_min' => 40, 'value_max' => 60, 'default_value' => 50, 'value_display' => '40-60%', 'unit' => '%', 'sort_order' => 4],
            ['clinical_scenario' => 'routine', 'parameter' => 'I:E', 'value_min' => null, 'value_max' => null, 'default_value' => null, 'value_display' => '1:2', 'unit' => null, 'sort_order' => 5],
            ['clinical_scenario' => 'routine', 'parameter' => 'Pplat', 'value_min' => null, 'value_max' => 30, 'default_value' => null, 'value_display' => '<30', 'unit' => 'cmH2O', 'sort_order' => 6, 'warning' => 'Pplat mục tiêu <30'],

            // Hen / COPD
            ['clinical_scenario' => 'asthma_copd', 'parameter' => 'Vt', 'value_min' => 6, 'value_max' => 8, 'default_value' => 7, 'value_display' => '6-8 mL/kg PBW', 'unit' => 'mL', 'sort_order' => 1],
            ['clinical_scenario' => 'asthma_copd', 'parameter' => 'RR', 'value_min' => 8, 'value_max' => 10, 'default_value' => 8, 'value_display' => '8-10', 'unit' => 'lần/phút', 'sort_order' => 2, 'warning' => 'RR thấp tránh bẫy khí'],
            ['clinical_scenario' => 'asthma_copd', 'parameter' => 'PEEP', 'value_min' => 0, 'value_max' => 5, 'default_value' => 0, 'value_display' => '0-5', 'unit' => 'cmH2O', 'sort_order' => 3],
            ['clinical_scenario' => 'asthma_copd', 'parameter' => 'I:E', 'value_min' => null, 'value_max' => null, 'default_value' => null, 'value_display' => '1:3 - 1:5', 'unit' => null, 'sort_order' => 4, 'warning' => 'Kéo dài thì thở ra'],

            // ARDS
            ['clinical_scenario' => 'ards', 'parameter' => 'Vt', 'value_min' => 4, 'value_max' => 6, 'default_value' => 6, 'value_display' => '4-6 mL/kg PBW', 'unit' => 'mL', 'sort_order' => 1, 'warning' => 'Bảo vệ phổi - Vt thấp'],
            ['clinical_scenario' => 'ards', 'parameter' => 'RR', 'value_min' => 16, 'value_max' => 24, 'default_value' => 16, 'value_display' => '16-24', 'unit' => 'lần/phút', 'sort_order' => 2],
            ['clinical_scenario' => 'ards', 'parameter' => 'PEEP', 'value_min' => 5, 'value_max' => 15, 'default_value' => 10, 'value_display' => '≥5 (thường 10-15)', 'unit' => 'cmH2O', 'sort_order' => 3],
            ['clinical_scenario' => 'ards', 'parameter' => 'Pplat', 'value_min' => null, 'value_max' => 30, 'default_value' => null, 'value_display' => '<30', 'unit' => 'cmH2O', 'sort_order' => 4, 'warning' => 'Bắt buộc Pplat <30'],

            // Nội soi CO2
            ['clinical_scenario' => 'co2_endoscopy', 'parameter' => 'Vt', 'value_min' => 6, 'value_max' => 7, 'default_value' => 6.5, 'value_display' => '6-7 mL/kg PBW', 'unit' => 'mL', 'sort_order' => 1],
            ['clinical_scenario' => 'co2_endoscopy', 'parameter' => 'RR', 'value_min' => 14, 'value_max' => 18, 'default_value' => 14, 'value_display' => '14-18', 'unit' => 'lần/phút', 'sort_order' => 2, 'warning' => 'Tăng RR thải CO2'],
            ['clinical_scenario' => 'co2_endoscopy', 'parameter' => 'PEEP', 'value_min' => 5, 'value_max' => 5, 'default_value' => 5, 'value_display' => '5', 'unit' => 'cmH2O', 'sort_order' => 3],
        ];

        foreach ($presets as $p) {
            DB::table('ventilator_presets')->updateOrInsert(
                ['clinical_scenario' => $p['clinical_scenario'], 'parameter' => $p['parameter']],
                [...$p, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
