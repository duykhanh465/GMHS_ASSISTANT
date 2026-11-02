<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Sheet Cap_cuu_CRISIS — docs/clinical/excel-original/Dr.Tom.xlsx
 */
class EmergencyProtocolSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'situation' => 'Ngừng tuần hoàn PEA/vô tâm thu',
                'signs' => 'Không mạch, nhịp không sốc điện được',
                'immediate_action' => 'Gọi hỗ trợ, CPR 100-120/ph, O2 100%, EtCO2, tìm Hs/Ts',
                'adult_dose' => 'Adrenaline 1 mg IV mỗi 3-5 phút',
                'result_by_weight' => '1 mg',
                'monitoring' => 'EtCO2 >10 khi CPR, ROSC khi EtCO2 tăng đột ngột',
                'differential' => 'Thiếu oxy, giảm thể tích, tăng K+, toan, tràn khí áp lực, thuyên tắc',
                'note' => 'Không trì hoãn CPR để tìm nguyên nhân',
                'source_ref' => 'Emergency Manual',
                'sort_order' => 1,
                'translations' => json_encode([
                    'en' => [
                        'situation' => 'Cardiac arrest PEA / asystole',
                        'signs' => 'No pulse, non-shockable rhythm',
                        'immediate_action' => 'Call for help, CPR 100-120/min, O2 100%, EtCO2, search Hs/Ts',
                        'adult_dose' => 'Adrenaline 1 mg IV every 3-5 min',
                        'result_by_weight' => '1 mg',
                        'monitoring' => 'EtCO2 >10 during CPR; sudden rise suggests ROSC',
                        'differential' => 'Hypoxia, hypovolemia, hyperK, acidosis, tension pneumothorax, embolism',
                        'note' => 'Do not delay CPR to find the cause',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'situation' => 'Rung thất/nhịp nhanh thất vô mạch',
                'signs' => 'Không mạch, nhịp sốc điện được',
                'immediate_action' => 'CPR + sốc điện 120-200 J hai pha; lặp lại mỗi 2 phút',
                'adult_dose' => 'Adrenaline 1 mg sau sốc lần 2; amiodarone 300 mg sau sốc lần 3',
                'result_by_weight' => 'Adrenaline 1 mg; Amiodarone 300 mg',
                'monitoring' => 'EtCO2, mạch, ECG',
                'differential' => 'Hs/Ts',
                'note' => 'Sau ROSC chăm sóc sau ngừng tim',
                'source_ref' => 'Emergency Manual',
                'sort_order' => 2,
                'translations' => json_encode([
                    'en' => [
                        'situation' => 'VF / pulseless VT',
                        'signs' => 'No pulse, shockable rhythm',
                        'immediate_action' => 'CPR + biphasic shock 120-200 J; repeat every 2 min',
                        'adult_dose' => 'Adrenaline 1 mg after 2nd shock; amiodarone 300 mg after 3rd shock',
                        'result_by_weight' => 'Adrenaline 1 mg; Amiodarone 300 mg',
                        'monitoring' => 'EtCO2, pulse, ECG',
                        'differential' => 'Hs/Ts',
                        'note' => 'Post-ROSC care after ROSC',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'situation' => 'Nhịp chậm có mạch',
                'signs' => 'HR <50 và giảm tưới máu',
                'immediate_action' => 'O2 100%, loại bỏ kích thích phế vị, kiểm tra mạch',
                'adult_dose' => 'Atropine 0,5-1 mg mỗi 3-5 phút; adrenaline 5-10 mcg nếu không đáp ứng',
                'result_by_weight' => 'Atropine 0,5-1 mg',
                'monitoring' => 'HA, ECG, SpO2',
                'differential' => 'Thiếu oxy, block cao, thuốc, vagal',
                'note' => 'Cân nhắc tạo nhịp nếu không đáp ứng',
                'source_ref' => 'Emergency Manual',
                'sort_order' => 3,
                'translations' => json_encode([
                    'en' => [
                        'situation' => 'Bradycardia with pulse',
                        'signs' => 'HR <50 with hypoperfusion',
                        'immediate_action' => 'O2 100%, remove vagal stimulus, check pulse',
                        'adult_dose' => 'Atropine 0.5-1 mg every 3-5 min; adrenaline 5-10 mcg if no response',
                        'result_by_weight' => 'Atropine 0.5-1 mg',
                        'monitoring' => 'BP, ECG, SpO2',
                        'differential' => 'Hypoxia, high block, drugs, vagal',
                        'note' => 'Consider pacing if no response',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'situation' => 'Phản vệ',
                'signs' => 'Tụt HA, co thắt PQ, mày đay/phù mạch',
                'immediate_action' => 'Ngưng tác nhân, O2 100%, dịch nhanh, gọi hỗ trợ',
                'adult_dose' => 'Adrenaline 10-100 mcg IV tăng dần; nếu không IV: 0,3-0,5 mg IM',
                'result_by_weight' => 'IV 1-10 mL nếu pha 10 mcg/mL; IM 0,3-0,5 mg',
                'monitoring' => 'HA, HR, SpO2, EtCO2, tryptase',
                'differential' => 'Tụt HA do mê sâu/mất máu, thuyên tắc, co thắt PQ đơn thuần',
                'note' => 'Adrenaline là thuốc chính; steroid/kháng H chỉ hỗ trợ',
                'source_ref' => 'Emergency Manual; STANFORD',
                'sort_order' => 4,
                'translations' => json_encode([
                    'en' => [
                        'situation' => 'Anaphylaxis',
                        'signs' => 'Hypotension, bronchospasm, urticaria / angioedema',
                        'immediate_action' => 'Stop trigger, O2 100%, rapid fluid, call for help',
                        'adult_dose' => 'Adrenaline 10-100 mcg IV titrated; if no IV: 0.3-0.5 mg IM',
                        'result_by_weight' => 'IV 1-10 mL if mixed 10 mcg/mL; IM 0.3-0.5 mg',
                        'monitoring' => 'BP, HR, SpO2, EtCO2, tryptase',
                        'differential' => 'Hypotension from deep anesthesia / bleeding, embolism, isolated bronchospasm',
                        'note' => 'Adrenaline is first-line; steroid/antihistamine are adjuncts',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'situation' => 'Co thắt phế quản nặng',
                'signs' => 'Ppeak tăng, EtCO2 dốc lên, ran rít/im lặng, khó thông khí',
                'immediate_action' => 'O2 100%, kiểm tra ống/máy, tăng sevo, kéo dài thở ra',
                'adult_dose' => 'Salbutamol khí dung 2,5-5 mg; MgSO4 2 g; ketamine 0,5-2 mg/kg; adrenaline 10 mcg IV',
                'result_by_weight' => 'MgSO4 2 g; Ketamine 33 - 130 mg',
                'monitoring' => 'Ppeak, EtCO2, SpO2, HA',
                'differential' => 'Tắc ống, gập ống, đặt sâu, phản vệ, tràn khí màng phổi',
                'note' => 'Ngắt máy bóp bóng giúp phân biệt tăng sức cản/giảm compliance',
                'source_ref' => 'Oxford; Emergency Manual',
                'sort_order' => 5,
                'translations' => json_encode([
                    'en' => [
                        'situation' => 'Severe bronchospasm',
                        'signs' => 'High Ppeak, upsloping EtCO2, wheeze/silent chest, hard to ventilate',
                        'immediate_action' => 'O2 100%, check tube/circuit, increase sevo, prolong expiration',
                        'adult_dose' => 'Nebulized salbutamol 2.5-5 mg; MgSO4 2 g; ketamine 0.5-2 mg/kg; adrenaline 10 mcg IV',
                        'result_by_weight' => 'MgSO4 2 g; Ketamine 33 - 130 mg',
                        'monitoring' => 'Ppeak, EtCO2, SpO2, BP',
                        'differential' => 'Tube obstruction/kink, endobronchial, anaphylaxis, pneumothorax',
                        'note' => 'Disconnect and bag to distinguish resistance vs compliance',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'situation' => 'Tăng áp lực đường thở',
                'signs' => 'Ppeak tăng, Vt giảm, SpO2 giảm',
                'immediate_action' => 'O2 100%, bóp tay, hút ống, kiểm tra circuit/ETT, nghe phổi',
                'adult_dose' => 'Điều trị theo nguyên nhân: salbutamol, giãn cơ, chọc kim nếu TKMP áp lực',
                'result_by_weight' => 'Theo nguyên nhân',
                'monitoring' => 'Ppeak/Pplat, EtCO2, SpO2',
                'differential' => 'Co thắt PQ, tắc/gập ống, TKMP, phù phổi, đặt sâu',
                'note' => 'So sánh Ppeak và Pplat nếu có',
                'source_ref' => 'Oxford/STANFORD',
                'sort_order' => 6,
                'translations' => json_encode([
                    'en' => [
                        'situation' => 'High airway pressure',
                        'signs' => 'High Ppeak, low Vt, falling SpO2',
                        'immediate_action' => 'O2 100%, bag, suction, check circuit/ETT, auscultate',
                        'adult_dose' => 'Treat the cause: salbutamol, relaxant, needle if tension PTX',
                        'result_by_weight' => 'By cause',
                        'monitoring' => 'Ppeak/Pplat, EtCO2, SpO2',
                        'differential' => 'Bronchospasm, tube kink/obstruction, PTX, pulmonary edema, endobronchial',
                        'note' => 'Compare Ppeak and Pplat when available',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'situation' => 'Tụt HA trong mổ',
                'signs' => 'MAP giảm, mạch nhanh/chậm tùy nguyên nhân',
                'immediate_action' => 'Giảm mê nếu quá sâu, O2, đánh giá mất máu/dịch/block/tim/phổi',
                'adult_dose' => 'Ephedrine 6-9 mg hoặc phenylephrine 50-100 mcg; norad nếu sốc',
                'result_by_weight' => 'theo nguyên nhân',
                'monitoring' => 'MAP, HR, EBL, nước tiểu, lactate',
                'differential' => 'Mê sâu, mất máu, phản vệ, TKMP, thuyên tắc, suy tim',
                'note' => 'Không chỉ bơm vận mạch; phải tìm nguyên nhân',
                'source_ref' => 'STANFORD; phác đồ khoa',
                'sort_order' => 7,
                'translations' => json_encode([
                    'en' => [
                        'situation' => 'Intraoperative hypotension',
                        'signs' => 'Falling MAP, HR fast/slow depending on cause',
                        'immediate_action' => 'Lighten anesthesia if too deep, O2, assess bleeding/fluid/block/heart/lung',
                        'adult_dose' => 'Ephedrine 6-9 mg or phenylephrine 50-100 mcg; noradrenaline if shock',
                        'result_by_weight' => 'by cause',
                        'monitoring' => 'MAP, HR, EBL, urine output, lactate',
                        'differential' => 'Deep anesthesia, bleeding, anaphylaxis, PTX, embolism, heart failure',
                        'note' => 'Do not only push vasopressors; find the cause',
                    ],
                ], JSON_UNESCAPED_UNICODE),
            ],
        ];

        foreach ($rows as $r) {
            DB::table('emergency_protocols')->updateOrInsert(
                ['situation' => $r['situation']],
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
        $emId = $sourceIds['Emergency Manual — Cognitive aids quanh mổ (2022)'] ?? null;
        $stanfordId = $sourceIds['STANFORD CA-1 2022 (bản dịch 25-09-23)'] ?? null;
        $oxfordId = $sourceIds['Sổ tay gây mê Oxford — GMHS.vn (5th edition)'] ?? null;
        $khoaId = $sourceIds['Phác đồ khoa — Sổ tay thực hành nhanh (16/06/2026)'] ?? null;
        $type = 'App\\Models\\EmergencyProtocol';

        foreach (DB::table('emergency_protocols')->get(['id', 'source_ref']) as $row) {
            $ref = mb_strtolower((string) $row->source_ref);
            $attach = function (?int $sourceId, string $priority) use ($type, $row) {
                if (! $sourceId) {
                    return;
                }
                DB::table('sourceables')->updateOrInsert(
                    ['source_id' => $sourceId, 'sourceable_type' => $type, 'sourceable_id' => $row->id],
                    ['priority' => $priority, 'created_at' => now(), 'updated_at' => now()]
                );
            };
            if (str_contains($ref, 'emergency')) {
                $attach($emId, 'A');
            }
            if (str_contains($ref, 'stanford')) {
                $attach($stanfordId, str_contains($ref, 'emergency') ? 'B' : 'A');
            }
            if (str_contains($ref, 'oxford')) {
                $attach($oxfordId, 'A');
            }
            if (str_contains($ref, 'phác đồ')) {
                $attach($khoaId, 'A');
            }
        }
    }
}
