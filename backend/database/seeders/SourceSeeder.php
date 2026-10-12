<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => "Miller's Anesthesia, 9th Edition", 'document_type' => 'Sách', 'priority' => 'A', 'notes' => 'Tham khảo chính cho liều gây mê, vận mạch — chương Pharmacology'],
            ['name' => "Morgan & Mikhail's Clinical Anesthesiology, 6th", 'document_type' => 'Sách', 'priority' => 'A', 'notes' => 'Công thức PBW/AdjBW, 4-2-1; liều khởi mê chuẩn'],
            ['name' => 'ARDSNet — Ventilator Protocol', 'document_type' => 'Guideline', 'priority' => 'A', 'notes' => 'Vt 6 mL/kg PBW (4-6 khi ARDS), PEEP/FiO2 table, Pplat <30'],
            ['name' => 'ASA Practice Guidelines', 'document_type' => 'Guideline', 'priority' => 'B', 'notes' => 'ASA I–V, theo dõi EtCO2/SpO2, bảo vệ phổi thường quy'],
            ['name' => 'Excel gốc — 9 sheets (Thuoc_GMHS, May_tho, Van_mach...)', 'document_type' => 'Nội bộ', 'priority' => 'C', 'notes' => 'File chú đưa: docs/clinical/excel-original/ (không push GitHub) — đối chiếu golden test'],
        ];

        foreach ($rows as $r) {
            DB::table('sources')->updateOrInsert(
                ['name' => $r['name']],
                array_merge($r, ['source_updated_at' => now()->toDateString(), 'created_at' => now(), 'updated_at' => now()])
            );
        }

        // --- Link each important datum -> source via sourceables (morphic pivot) ---
        // Must run after DrugSeeder + VentilatorPresetSeeder.
        $sourceIds = DB::table('sources')->pluck('id', 'name');
        $drugs = DB::table('drugs')->get(['id', 'name']);
        $presets = DB::table('ventilator_presets')->get(['id', 'clinical_scenario', 'parameter']);

        if ($sourceIds->isEmpty() || $drugs->isEmpty()) {
            return;
        }

        $millerId = $sourceIds["Miller's Anesthesia, 9th Edition"] ?? null;
        $morganId = $sourceIds["Morgan & Mikhail's Clinical Anesthesiology, 6th"] ?? null;
        $ardsnetId = $sourceIds['ARDSNet — Ventilator Protocol'] ?? null;
        $asaId = $sourceIds['ASA Practice Guidelines'] ?? null;
        $excelId = $sourceIds['Excel gốc — 9 sheets (Thuoc_GMHS, May_tho, Van_mach...)'] ?? null;

        $attach = function (int $sourceId, string $type, int $typeId, ?string $priority = null) {
            DB::table('sourceables')->updateOrInsert(
                ['source_id' => $sourceId, 'sourceable_type' => $type, 'sourceable_id' => $typeId],
                ['priority' => $priority, 'created_at' => now(), 'updated_at' => now()]
            );
        };

        // 1) Every drug has at least Miller + Excel cross-check; key drugs also have Morgan
        $morganDrugs = ['Propofol', 'Fentanyl', 'Rocuronium', 'Noradrenaline'];
        foreach ($drugs as $d) {
            if ($millerId) $attach($millerId, 'App\\Models\\Drug', $d->id, 'A');
            if ($excelId) $attach($excelId, 'App\\Models\\Drug', $d->id, 'C');
            if ($morganId && in_array($d->name, $morganDrugs, true)) {
                $attach($morganId, 'App\\Models\\Drug', $d->id, 'A');
            }
        }

        // 2) Ventilator presets: routine -> ASA + Morgan/Miller ; ards -> ARDSNet ; others -> Miller
        foreach ($presets as $p) {
            $type = 'App\\Models\\VentilatorPreset';
            if ($p->clinical_scenario === 'ards' && $ardsnetId) {
                $attach($ardsnetId, $type, $p->id, 'A');
                if ($millerId) $attach($millerId, $type, $p->id, 'B');
            } elseif ($p->clinical_scenario === 'routine') {
                if ($asaId) $attach($asaId, $type, $p->id, 'A');
                if ($morganId) $attach($morganId, $type, $p->id, 'A');
                if ($excelId) $attach($excelId, $type, $p->id, 'C');
            } elseif ($p->clinical_scenario === 'asthma_copd') {
                if ($millerId) $attach($millerId, $type, $p->id, 'A');
                if ($excelId) $attach($excelId, $type, $p->id, 'C');
            } elseif ($p->clinical_scenario === 'co2_endoscopy') {
                if ($millerId) $attach($millerId, $type, $p->id, 'A');
                if ($excelId) $attach($excelId, $type, $p->id, 'C');
            } else {
                if ($millerId) $attach($millerId, $type, $p->id, 'A');
            }
        }
    }
}
