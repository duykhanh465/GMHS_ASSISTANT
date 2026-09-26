<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DrugCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Thuốc khởi mê', 'slug' => 'khoi-me', 'sort_order' => 1],
            ['name' => 'Opioid', 'slug' => 'opioid', 'sort_order' => 2],
            ['name' => 'Giãn cơ', 'slug' => 'gian-co', 'sort_order' => 3],
            ['name' => 'Vận mạch / Inotrope', 'slug' => 'van-mach', 'sort_order' => 4],
            ['name' => 'Giảm đau', 'slug' => 'giam-dau', 'sort_order' => 5],
            ['name' => 'Chống nôn (PONV)', 'slug' => 'ponv', 'sort_order' => 6],
            ['name' => 'Hóa giải', 'slug' => 'hoa-giai', 'sort_order' => 7],
        ];

        foreach ($categories as $cat) {
            DB::table('drug_categories')->updateOrInsert(
                ['slug' => $cat['slug']],
                [...$cat, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
