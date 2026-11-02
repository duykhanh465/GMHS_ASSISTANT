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
            ['name' => 'Thuốc khởi mê', 'slug' => 'khoi-me', 'sort_order' => 1, 'translations' => [
                'en' => ['name' => 'Induction'], 'fr' => ['name' => 'Induction'], 'ja' => ['name' => '導入'],
                'ko' => ['name' => '유도'], 'zh' => ['name' => '诱导'], 'de' => ['name' => 'Einleitung'], 'es' => ['name' => 'Inducción'],
            ]],
            ['name' => 'Opioid', 'slug' => 'opioid', 'sort_order' => 2, 'translations' => [
                'en' => ['name' => 'Opioid'], 'fr' => ['name' => 'Opioïde'], 'ja' => ['name' => 'オピオイド'],
                'ko' => ['name' => '오피오이드'], 'zh' => ['name' => '阿片类'], 'de' => ['name' => 'Opioid'], 'es' => ['name' => 'Opioide'],
            ]],
            ['name' => 'Giãn cơ', 'slug' => 'gian-co', 'sort_order' => 3, 'translations' => [
                'en' => ['name' => 'Muscle relaxant'], 'fr' => ['name' => 'Curare'], 'ja' => ['name' => '筋弛緩'],
                'ko' => ['name' => '근이완'], 'zh' => ['name' => '肌松'], 'de' => ['name' => 'Muskelrelaxans'], 'es' => ['name' => 'Relajante'],
            ]],
            ['name' => 'Vận mạch / Inotrope', 'slug' => 'van-mach', 'sort_order' => 4, 'translations' => [
                'en' => ['name' => 'Vasopressor / Inotrope'], 'fr' => ['name' => 'Vasopresseur'], 'ja' => ['name' => '血管作動'],
                'ko' => ['name' => '혈관수축제'], 'zh' => ['name' => '血管活性'], 'de' => ['name' => 'Vasopressor'], 'es' => ['name' => 'Vasopresor'],
            ]],
            ['name' => 'Giảm đau', 'slug' => 'giam-dau', 'sort_order' => 5, 'translations' => [
                'en' => ['name' => 'Analgesia'], 'fr' => ['name' => 'Analgésie'], 'ja' => ['name' => '鎮痛'],
                'ko' => ['name' => '진통'], 'zh' => ['name' => '镇痛'], 'de' => ['name' => 'Analgesie'], 'es' => ['name' => 'Analgesia'],
            ]],
            ['name' => 'Chống nôn (PONV)', 'slug' => 'ponv', 'sort_order' => 6, 'translations' => [
                'en' => ['name' => 'PONV'], 'fr' => ['name' => 'NVPO'], 'ja' => ['name' => 'PONV'],
                'ko' => ['name' => 'PONV'], 'zh' => ['name' => 'PONV'], 'de' => ['name' => 'PONV'], 'es' => ['name' => 'PONV'],
            ]],
            ['name' => 'Hóa giải', 'slug' => 'hoa-giai', 'sort_order' => 7, 'translations' => [
                'en' => ['name' => 'Reversal'], 'fr' => ['name' => 'Décurarisation'], 'ja' => ['name' => '拮抗'],
                'ko' => ['name' => '역전'], 'zh' => ['name' => '拮抗'], 'de' => ['name' => 'Reversierung'], 'es' => ['name' => 'Reversión'],
            ]],
        ];

        foreach ($categories as $cat) {
            $translations = json_encode($cat['translations'], JSON_UNESCAPED_UNICODE);
            unset($cat['translations']);
            DB::table('drug_categories')->updateOrInsert(
                ['slug' => $cat['slug']],
                [...$cat, 'translations' => $translations, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
