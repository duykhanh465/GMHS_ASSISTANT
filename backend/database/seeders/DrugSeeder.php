<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DrugSeeder extends Seeder
{
    public function run(): void
    {
        $cats = DB::table('drug_categories')->pluck('id', 'slug');

        $drugs = [
            // Thuốc khởi mê
            ['cat' => 'khoi-me', 'name' => 'Propofol', 'generic_name' => 'Propofol', 'unit' => 'mg', 'dose_text' => '1.5-2.5 mg/kg', 'dose_min_per_kg' => 1.5, 'dose_max_per_kg' => 2.5, 'weight_basis' => 'actual', 'preparation' => 'Dùng trực tiếp 10mg/mL', 'warning' => 'Tụt HA, ức chế hô hấp'],
            ['cat' => 'khoi-me', 'name' => 'Etomidate', 'generic_name' => 'Etomidate', 'unit' => 'mg', 'dose_text' => '0.2-0.3 mg/kg', 'dose_min_per_kg' => 0.2, 'dose_max_per_kg' => 0.3, 'weight_basis' => 'actual', 'preparation' => 'Dùng trực tiếp 2mg/mL'],
            ['cat' => 'khoi-me', 'name' => 'Ketamine', 'generic_name' => 'Ketamine', 'unit' => 'mg', 'dose_text' => '1-2 mg/kg IV', 'dose_min_per_kg' => 1, 'dose_max_per_kg' => 2, 'weight_basis' => 'actual'],

            // Opioid
            ['cat' => 'opioid', 'name' => 'Fentanyl', 'generic_name' => 'Fentanyl', 'unit' => 'mcg', 'dose_text' => '1-3 mcg/kg', 'dose_min_per_kg' => 1, 'dose_max_per_kg' => 3, 'weight_basis' => 'actual', 'preparation' => 'Ống 100mcg/2mL'],
            ['cat' => 'opioid', 'name' => 'Sufentanil', 'generic_name' => 'Sufentanil', 'unit' => 'mcg', 'dose_text' => '0.1-0.3 mcg/kg', 'dose_min_per_kg' => 0.1, 'dose_max_per_kg' => 0.3, 'weight_basis' => 'actual'],

            // Giãn cơ
            ['cat' => 'gian-co', 'name' => 'Rocuronium', 'generic_name' => 'Rocuronium', 'unit' => 'mg', 'dose_text' => '0.6-1.2 mg/kg', 'dose_min_per_kg' => 0.6, 'dose_max_per_kg' => 1.2, 'weight_basis' => 'actual', 'preparation' => '10mg/mL', 'monitoring' => 'TOF'],
            ['cat' => 'gian-co', 'name' => 'Vecuronium', 'generic_name' => 'Vecuronium', 'unit' => 'mg', 'dose_text' => '0.08-0.1 mg/kg', 'dose_min_per_kg' => 0.08, 'dose_max_per_kg' => 0.1, 'weight_basis' => 'actual'],
            ['cat' => 'gian-co', 'name' => 'Succinylcholine', 'generic_name' => 'Suxamethonium', 'unit' => 'mg', 'dose_text' => '1-1.5 mg/kg', 'dose_min_per_kg' => 1, 'dose_max_per_kg' => 1.5, 'weight_basis' => 'actual', 'warning' => 'Tăng K+, loạn nhịp'],

            // Vận mạch
            ['cat' => 'van-mach', 'name' => 'Noradrenaline', 'generic_name' => 'Norepinephrine', 'unit' => 'mcg/kg/phút', 'dose_text' => '0.02-0.2 mcg/kg/phút', 'dose_min_per_kg' => 0.02, 'dose_max_per_kg' => 0.2, 'weight_basis' => 'actual', 'preparation' => '4mg/40mL = 100mcg/mL hoặc 4mg/100mL = 40mcg/mL', 'warning' => 'CHÚ Ý nồng độ pha: 100 vs 40 mcg/mL'],
            ['cat' => 'van-mach', 'name' => 'Phenylephrine', 'generic_name' => 'Phenylephrine', 'unit' => 'mcg', 'dose_text' => '50-100 mcg bolus', 'dose_min_per_kg' => null, 'dose_max_per_kg' => null, 'weight_basis' => 'fixed', 'preparation' => 'Pha 100mcg/mL'],
            ['cat' => 'van-mach', 'name' => 'Ephedrine', 'generic_name' => 'Ephedrine', 'unit' => 'mg', 'dose_text' => '5-10 mg bolus', 'dose_min_per_kg' => null, 'dose_max_per_kg' => null, 'weight_basis' => 'fixed'],
            ['cat' => 'van-mach', 'name' => 'Adrenaline', 'generic_name' => 'Epinephrine', 'unit' => 'mcg/kg/phút', 'dose_text' => '0.02-0.1 mcg/kg/phút', 'dose_min_per_kg' => 0.02, 'dose_max_per_kg' => 0.1, 'weight_basis' => 'actual', 'preparation' => '1mg/50mL'],

            // Giảm đau
            ['cat' => 'giam-dau', 'name' => 'Paracetamol', 'generic_name' => 'Paracetamol', 'unit' => 'mg', 'dose_text' => '15 mg/kg', 'dose_min_per_kg' => 15, 'dose_max_per_kg' => 15, 'weight_basis' => 'actual', 'timing' => 'Truyền 15 phút'],
            ['cat' => 'giam-dau', 'name' => 'Ketorolac', 'generic_name' => 'Ketorolac', 'unit' => 'mg', 'dose_text' => '30 mg', 'dose_min_per_kg' => null, 'dose_max_per_kg' => null, 'weight_basis' => 'fixed'],

            // PONV
            ['cat' => 'ponv', 'name' => 'Ondansetron', 'generic_name' => 'Ondansetron', 'unit' => 'mg', 'dose_text' => '4 mg', 'dose_min_per_kg' => null, 'dose_max_per_kg' => null, 'weight_basis' => 'fixed', 'timing' => 'Cuối ca mổ'],
            ['cat' => 'ponv', 'name' => 'Dexamethasone', 'generic_name' => 'Dexamethasone', 'unit' => 'mg', 'dose_text' => '4-8 mg', 'dose_min_per_kg' => null, 'dose_max_per_kg' => null, 'weight_basis' => 'fixed', 'timing' => 'Đầu ca mổ'],

            // Hóa giải
            ['cat' => 'hoa-giai', 'name' => 'Sugammadex', 'generic_name' => 'Sugammadex', 'unit' => 'mg', 'dose_text' => '2-4 mg/kg', 'dose_min_per_kg' => 2, 'dose_max_per_kg' => 4, 'weight_basis' => 'actual', 'preparation' => '100mg/mL'],
            ['cat' => 'hoa-giai', 'name' => 'Neostigmine', 'generic_name' => 'Neostigmine', 'unit' => 'mg', 'dose_text' => '0.04 mg/kg', 'dose_min_per_kg' => 0.04, 'dose_max_per_kg' => 0.04, 'weight_basis' => 'actual', 'timing' => 'Kèm Atropine 0.02mg/kg'],
        ];

        $catIds = $cats;
        foreach ($drugs as $d) {
            $catId = $catIds[$d['cat']] ?? null;
            if (!$catId) continue;
            DB::table('drugs')->updateOrInsert(
                ['name' => $d['name']],
                [
                    'drug_category_id' => $catId,
                    'generic_name' => $d['generic_name'] ?? null,
                    'unit' => $d['unit'] ?? null,
                    'dose_text' => $d['dose_text'] ?? null,
                    'dose_min_per_kg' => $d['dose_min_per_kg'] ?? null,
                    'dose_max_per_kg' => $d['dose_max_per_kg'] ?? null,
                    'weight_basis' => $d['weight_basis'] ?? 'actual',
                    'preparation' => $d['preparation'] ?? null,
                    'indication' => $d['indication'] ?? null,
                    'timing' => $d['timing'] ?? null,
                    'monitoring' => $d['monitoring'] ?? null,
                    'warning' => $d['warning'] ?? null,
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
