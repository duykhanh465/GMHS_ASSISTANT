<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Nguồn chuẩn lấy từ sheet Nguon trong
 * docs/clinical/excel-original/Dr.Tom.xlsx (KHÔNG push file này lên GitHub).
 */
class SourceSeeder extends Seeder
{
    public function run(): void
    {
        // Xóa các dòng nguồn cũ đặt tên sai (sách nước ngoài không có trong Excel)
        $legacy = [
            "Miller's Anesthesia, 9th Edition",
            "Morgan & Mikhail's Clinical Anesthesiology, 6th",
            'ARDSNet — Ventilator Protocol',
            'ASA Practice Guidelines',
            'Excel gốc — 9 sheets (Thuoc_GMHS, May_tho, Van_mach...)',
        ];
        DB::table('sources')->whereIn('name', $legacy)->delete();

        $rows = [
            [
                'name' => 'Phác đồ khoa — Sổ tay thực hành nhanh (16/06/2026)',
                'document_type' => 'Phác đồ khoa (DOCX)',
                'priority' => 'A',
                'notes' => 'Liều thuốc, cách pha, công thức mL/h: fentanyl, propofol, giãn cơ, hóa giải, vận mạch, giảm đau, PONV, TTS. Ưu tiên cao nhất theo khoa — dùng đúng thuốc/nồng độ thực tế tại khoa.',
                'source_updated_at' => '2026-06-16',
                'translations' => [
                    'en' => [
                        'name' => 'Department protocol — Quick practice handbook (16/06/2026)',
                        'document_type' => 'Department protocol (DOCX)',
                        'notes' => 'Doses, dilutions, mL/h formulas: fentanyl, propofol, relaxants, reversal, vasopressors, analgesia, PONV, spinal. Highest priority — use the actual drugs/concentrations of the department.',
                    ],
                ],
            ],
            [
                'name' => 'Sách Gây mê hồi sức Huế tập 2 (2021)',
                'document_type' => 'Giáo trình (PDF)',
                'priority' => 'A',
                'notes' => 'Thông khí nhân tạo, COPD, ARDS, bệnh kèm. Vt 6–7 mL/kg PBW, RR 12–16, PEEP, Pplat <30; COPD RR 8–10, I:E dài. Cao cho phần sinh lý/thông khí.',
                'source_updated_at' => null,
                'translations' => [
                    'en' => [
                        'name' => 'Hue Anesthesia & ICU textbook vol. 2 (2021)',
                        'document_type' => 'Textbook (PDF)',
                        'notes' => 'Mechanical ventilation, COPD, ARDS, comorbidities. Vt 6–7 mL/kg PBW, RR 12–16, PEEP, Pplat <30; COPD RR 8–10, long I:E. Reference for physiology/ventilation.',
                    ],
                ],
            ],
            [
                'name' => 'Sổ tay gây mê Oxford — GMHS.vn (5th edition)',
                'document_type' => 'Sách dịch (PDF)',
                'priority' => 'A',
                'notes' => 'Cấp cứu gây mê, thuốc khởi mê, co thắt phế quản. Giảm liều propofol ở người già/sốc; ketamine/etomidate; salbutamol/MgSO4/adrenaline trong co thắt PQ. Đối chiếu với phác đồ khoa.',
                'source_updated_at' => null,
                'translations' => [
                    'en' => [
                        'name' => 'Oxford anesthesia handbook — GMHS.vn (5th edition)',
                        'document_type' => 'Translated book (PDF)',
                        'notes' => 'Anesthesia emergencies, induction drugs, bronchospasm. Reduce propofol in elderly/shock; ketamine/etomidate; salbutamol/MgSO4/adrenaline for bronchospasm. Cross-check with department protocol.',
                    ],
                ],
            ],
            [
                'name' => 'STANFORD CA-1 2022 (bản dịch 25-09-23)',
                'document_type' => 'Sổ tay (PDF)',
                'priority' => 'B',
                'notes' => 'Theo dõi, vận mạch, phản vệ, xử trí tụt HA. An toàn thuốc, monitor, adrenaline phản vệ. Dùng để tăng cảnh báo và checklist.',
                'source_updated_at' => null,
                'translations' => [
                    'en' => [
                        'name' => 'STANFORD CA-1 2022 (translated 25-09-23)',
                        'document_type' => 'Handbook (PDF)',
                        'notes' => 'Monitoring, vasopressors, anaphylaxis, hypotension management. Drug safety, monitoring, anaphylaxis adrenaline. For alerts and checklists.',
                    ],
                ],
            ],
            [
                'name' => 'Emergency Manual — Cognitive aids quanh mổ (2022)',
                'document_type' => 'Emergency manual (PDF)',
                'priority' => 'A',
                'notes' => 'CPR, phản vệ, co thắt phế quản, tăng áp lực đường thở, tụt HA (bài 1–27). Không thay thế phác đồ ALS/ACLS hiện hành của bệnh viện.',
                'source_updated_at' => null,
                'translations' => [
                    'en' => [
                        'name' => 'Emergency Manual — Perioperative cognitive aids (2022)',
                        'document_type' => 'Emergency manual (PDF)',
                        'notes' => 'CPR, anaphylaxis, bronchospasm, high airway pressure, hypotension (items 1–27). Does not replace the hospital ALS/ACLS protocol.',
                    ],
                ],
            ],
            [
                'name' => '500 Quy trình kỹ thuật GMHS (bản unicode)',
                'document_type' => 'Quy trình kỹ thuật (DOCX)',
                'priority' => 'B',
                'notes' => 'Liều bupivacain, fentanyl/morphin tủy sống, bù dịch 5–10 mL/kg, chống chỉ định. Tham khảo trong nước — đối chiếu với phác đồ khoa.',
                'source_updated_at' => null,
                'translations' => [
                    'en' => [
                        'name' => '500 GMHS technical procedures (unicode)',
                        'document_type' => 'Technical procedures (DOCX)',
                        'notes' => 'Bupivacaine, intrathecal fentanyl/morphine doses, fluid 5–10 mL/kg, contraindications. Local reference — cross-check with department protocol.',
                    ],
                ],
            ],
        ];

        foreach ($rows as $r) {
            $row = $r;
            if (isset($row['translations']) && is_array($row['translations'])) {
                $row['translations'] = json_encode($row['translations'], JSON_UNESCAPED_UNICODE);
            }
            DB::table('sources')->updateOrInsert(
                ['name' => $r['name']],
                array_merge($row, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // --- Link mỗi dữ liệu quan trọng -> nguồn via sourceables ---
        // Must run after DrugSeeder + VentilatorPresetSeeder.
        $sourceIds = DB::table('sources')->pluck('id', 'name');
        $drugs = DB::table('drugs')->get(['id', 'name']);
        $presets = DB::table('ventilator_presets')->get(['id', 'clinical_scenario', 'parameter']);

        if ($sourceIds->isEmpty() || $drugs->isEmpty()) {
            return;
        }

        $khoaId = $sourceIds['Phác đồ khoa — Sổ tay thực hành nhanh (16/06/2026)'] ?? null;
        $hueId = $sourceIds['Sách Gây mê hồi sức Huế tập 2 (2021)'] ?? null;
        $oxfordId = $sourceIds['Sổ tay gây mê Oxford — GMHS.vn (5th edition)'] ?? null;
        $stanfordId = $sourceIds['STANFORD CA-1 2022 (bản dịch 25-09-23)'] ?? null;
        $emId = $sourceIds['Emergency Manual — Cognitive aids quanh mổ (2022)'] ?? null;

        $attach = function (int $sourceId, string $type, int $typeId, ?string $priority = null) {
            DB::table('sourceables')->updateOrInsert(
                ['source_id' => $sourceId, 'sourceable_type' => $type, 'sourceable_id' => $typeId],
                ['priority' => $priority, 'created_at' => now(), 'updated_at' => now()]
            );
        };

        // 1) Thuốc: phác đồ khoa (A) + Oxford đối chiếu (B)
        foreach ($drugs as $d) {
            if ($khoaId) $attach($khoaId, 'App\\Models\\Drug', $d->id, 'A');
            if ($oxfordId) $attach($oxfordId, 'App\\Models\\Drug', $d->id, 'B');
        }

        // 2) Máy thở: thường quy/ARDS -> sách Huế (A) + phác đồ khoa (A);
        //    cấp cứu (co thắt/TTH) -> Oxford + Emergency Manual
        foreach ($presets as $p) {
            $type = 'App\\Models\\VentilatorPreset';
            if ($hueId) $attach($hueId, $type, $p->id, 'A');
            if ($khoaId) $attach($khoaId, $type, $p->id, 'A');
            if ($stanfordId) $attach($stanfordId, $type, $p->id, 'B');
            if ($emId) $attach($emId, $type, $p->id, 'B');
        }
    }
}
