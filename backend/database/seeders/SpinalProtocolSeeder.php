<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Sheet Gay_te_TTS — docs/clinical/excel-original/Dr.Tom.xlsx
 */
class SpinalProtocolSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'technique' => 'Bupivacain tủy sống',
                'adult_dose' => '8-12 mg; khung 5-12 mg',
                'reduced_dose' => '5-8 mg nếu >60 tuổi/thai kỳ/thể trạng yếu',
                'suggested_result' => '5-8 mg',
                'preparation' => 'Thường hyperbaric 0,5% tùy khoa',
                'monitoring' => 'HA, HR, mức phong bế',
                'event_management' => 'Tụt HA: phenylephrine/ephedrine + dịch; mạch chậm: atropine',
                'warning' => 'Tránh khi sốc/chưa bù đủ, hẹp van ĐMC khít, rối loạn đông máu nặng',
                'source_ref' => 'Phác đồ khoa; Quy trình GMHS',
                'sort_order' => 1,
                'translations' => json_encode([
                    'en' => [
                        'technique' => 'Intrathecal bupivacaine',
                        'adult_dose' => '8-12 mg; range 5-12 mg',
                        'reduced_dose' => '5-8 mg if >60 y/o, pregnancy, frail',
                        'suggested_result' => '5-8 mg',
                        'preparation' => 'Usually hyperbaric 0.5% per department',
                        'monitoring' => 'BP, HR, block level',
                        'event_management' => 'Hypotension: phenylephrine/ephedrine + fluid; bradycardia: atropine',
                        'warning' => 'Avoid in shock/unfilled, tight AS, severe coagulopathy',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'technique' => 'Fentanyl tủy sống',
                'adult_dose' => '10-25 mcg; thường 20-25 mcg',
                'reduced_dose' => '20 mcg',
                'suggested_result' => '20 mcg',
                'preparation' => 'Theo ống thuốc thực tế',
                'monitoring' => 'Ngứa, buồn nôn, hô hấp',
                'event_management' => 'Hỗ trợ giảm đau',
                'warning' => 'Theo dõi ức chế hô hấp',
                'source_ref' => 'Phác đồ khoa; Quy trình GMHS',
                'sort_order' => 2,
                'translations' => json_encode([
                    'en' => [
                        'technique' => 'Intrathecal fentanyl',
                        'adult_dose' => '10-25 mcg; usually 20-25 mcg',
                        'reduced_dose' => '20 mcg',
                        'suggested_result' => '20 mcg',
                        'preparation' => 'Per actual ampoule',
                        'monitoring' => 'Pruritus, nausea, respiration',
                        'event_management' => 'Support analgesia',
                        'warning' => 'Watch for respiratory depression',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'technique' => 'Morphin tủy sống',
                'adult_dose' => '100-300 mcg; thường 100-200 mcg',
                'reduced_dose' => '100 mcg',
                'suggested_result' => '100 mcg',
                'preparation' => 'Theo ống thuốc thực tế',
                'monitoring' => 'Hô hấp muộn, bí tiểu, ngứa, buồn nôn',
                'event_management' => 'Theo dõi hậu phẫu kéo dài',
                'warning' => 'Không dùng nếu không bảo đảm theo dõi',
                'source_ref' => 'Phác đồ khoa; Quy trình GMHS',
                'sort_order' => 3,
                'translations' => json_encode([
                    'en' => [
                        'technique' => 'Intrathecal morphine',
                        'adult_dose' => '100-300 mcg; usually 100-200 mcg',
                        'reduced_dose' => '100 mcg',
                        'suggested_result' => '100 mcg',
                        'preparation' => 'Per actual ampoule',
                        'monitoring' => 'Late respiratory depression, urinary retention, pruritus, nausea',
                        'event_management' => 'Prolonged postoperative monitoring',
                        'warning' => 'Do not use without guaranteed monitoring',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'technique' => 'Bù dịch trước/đồng thời',
                'adult_dose' => '5-10 mL/kg',
                'reduced_dose' => 'Thận trọng suy tim/người già',
                'suggested_result' => '325 - 650 mL',
                'preparation' => 'Tinh thể',
                'monitoring' => 'HA mỗi 2-5 phút giai đoạn đầu',
                'event_management' => 'Đánh giá đáp ứng dịch',
                'warning' => 'Không bù quá mức ở suy tim/tiền sản giật',
                'source_ref' => 'Quy trình GMHS',
                'sort_order' => 4,
                'translations' => json_encode([
                    'en' => [
                        'technique' => 'Preload / co-load fluid',
                        'adult_dose' => '5-10 mL/kg',
                        'reduced_dose' => 'Caution in heart failure / elderly',
                        'suggested_result' => '325 - 650 mL',
                        'preparation' => 'Crystalloid',
                        'monitoring' => 'BP every 2-5 min early phase',
                        'event_management' => 'Assess fluid response',
                        'warning' => 'Do not overload in heart failure / pre-eclampsia',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'technique' => 'Ephedrine',
                'adult_dose' => '6-9 mg bolus',
                'reduced_dose' => 'Titrate',
                'suggested_result' => '2-3 mL nếu pha 3 mg/mL',
                'preparation' => '30 mg/10 mL = 3 mg/mL',
                'monitoring' => 'HA, HR',
                'event_management' => 'Tụt HA + mạch chậm/bình thường',
                'warning' => 'Loại trừ block cao/mất máu',
                'source_ref' => 'Phác đồ khoa',
                'sort_order' => 5,
                'translations' => json_encode([
                    'en' => [
                        'technique' => 'Ephedrine',
                        'adult_dose' => '6-9 mg bolus',
                        'reduced_dose' => 'Titrate',
                        'suggested_result' => '2-3 mL if mixed 3 mg/mL',
                        'preparation' => '30 mg/10 mL = 3 mg/mL',
                        'monitoring' => 'BP, HR',
                        'event_management' => 'Hypotension + slow/normal HR',
                        'warning' => 'Exclude high block / bleeding',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'technique' => 'Phenylephrine',
                'adult_dose' => '50-100 mcg bolus',
                'reduced_dose' => 'Titrate',
                'suggested_result' => '0,5-1 mL nếu pha 100 mcg/mL',
                'preparation' => '10 mg/100 mL = 100 mcg/mL',
                'monitoring' => 'HA, HR',
                'event_management' => 'Tụt HA + mạch nhanh/giãn mạch',
                'warning' => 'Cẩn trọng nhịp chậm',
                'source_ref' => 'Phác đồ khoa',
                'sort_order' => 6,
                'translations' => json_encode([
                    'en' => [
                        'technique' => 'Phenylephrine',
                        'adult_dose' => '50-100 mcg bolus',
                        'reduced_dose' => 'Titrate',
                        'suggested_result' => '0.5-1 mL if mixed 100 mcg/mL',
                        'preparation' => '10 mg/100 mL = 100 mcg/mL',
                        'monitoring' => 'BP, HR',
                        'event_management' => 'Hypotension + tachycardia / vasodilation',
                        'warning' => 'Caution in bradycardia',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'technique' => 'Atropine',
                'adult_dose' => '0,5 mg bolus',
                'reduced_dose' => 'Titrate',
                'suggested_result' => '5 mL nếu pha 0,1 mg/mL',
                'preparation' => '1 mg/10 mL = 0,1 mg/mL',
                'monitoring' => 'HR, HA',
                'event_management' => 'Nhịp chậm có triệu chứng',
                'warning' => 'Tìm thiếu oxy/block cao',
                'source_ref' => 'Phác đồ khoa',
                'sort_order' => 7,
                'translations' => json_encode([
                    'en' => [
                        'technique' => 'Atropine',
                        'adult_dose' => '0.5 mg bolus',
                        'reduced_dose' => 'Titrate',
                        'suggested_result' => '5 mL if mixed 0.1 mg/mL',
                        'preparation' => '1 mg/10 mL = 0.1 mg/mL',
                        'monitoring' => 'HR, BP',
                        'event_management' => 'Symptomatic bradycardia',
                        'warning' => 'Look for hypoxia / high block',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
        ];

        foreach ($rows as $r) {
            DB::table('spinal_protocols')->updateOrInsert(
                ['technique' => $r['technique']],
                array_merge($r, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->attachSources();
    }

    private function attachSources(): void
    {
        $sourceIds = DB::table('sources')->pluck('id', 'name');
        $khoaId = $sourceIds['Phác đồ khoa — Sổ tay thực hành nhanh (16/06/2026)'] ?? null;
        $qtId = $sourceIds['500 Quy trình kỹ thuật GMHS (bản unicode)'] ?? null;
        $rows = DB::table('spinal_protocols')->get(['id', 'source_ref']);
        $type = 'App\\Models\\SpinalProtocol';

        foreach ($rows as $row) {
            $ref = (string) $row->source_ref;
            if ($khoaId && str_contains($ref, 'Phác đồ khoa')) {
                DB::table('sourceables')->updateOrInsert(
                    ['source_id' => $khoaId, 'sourceable_type' => $type, 'sourceable_id' => $row->id],
                    ['priority' => 'A', 'created_at' => now(), 'updated_at' => now()]
                );
            }
            if ($qtId && str_contains($ref, 'Quy trình')) {
                DB::table('sourceables')->updateOrInsert(
                    ['source_id' => $qtId, 'sourceable_type' => $type, 'sourceable_id' => $row->id],
                    ['priority' => 'B', 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
