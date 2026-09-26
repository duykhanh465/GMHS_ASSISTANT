<?php

namespace Tests\Unit;

use App\Services\Calculators\FluidCalculator;
use App\Services\Calculators\VentilatorCalculator;
use App\Services\Calculators\WeightCalculator;
use PHPUnit\Framework\TestCase;

class GoldenExcelComparisonTest extends TestCase
{
    /**
     * So sánh TRỰC TIẾP với file Excel Dr.Tom.xlsx
     * Sheet: Nhap_lieu + Cong_thuc + May_tho
     */
    public function test_so_sanh_voi_excel(): void
    {
        // =========================================================
        // DỮ LIỆU GỐC - Lấy từ Excel (bạn mở Excel đối chiếu)
        // =========================================================
        // Bệnh nhân: Nữ, 80 tuổi, 150cm, 65kg, ASA II, routine
        $gender = 'female';
        $heightCm = 150;
        $weightKg = 65;
        $scenario = 'routine';

        // ĐÁP ÁN TRONG EXCEL - Sheet Cong_thuc + May_tho
        $excelExpected = [
            'BMI'   => 28.9,        // = 65 / (1.5*1.5)
            'PBW'   => 43.3,        // = 45.5 + 0.91*(150-152.4)
            'AdjBW' => 52.0,        // = 43.3 + 0.4*(65-43.3) vì 65 > 1.2*43.3
            'Dich'  => 105,         // 4-2-1: 40 + 20 + 45
            'Vt_min'=> 260,         // 6 * 43.3
            'Vt_max'=> 303,         // 7 * 43.3
            'RR'    => 14,
            'PEEP'  => 5,
            'FiO2'  => '40-60%',
        ];

        // =========================================================
        // TÍNH TOÁN BẰNG CODE (3 máy Phase 3)
        // =========================================================
        $weight = new WeightCalculator();
        $fluid = new FluidCalculator();
        $vent = new VentilatorCalculator();

        $bmi   = $weight->bmi($weightKg, $heightCm);
        $pbw   = $weight->pbw($gender, $heightCm);
        $adjbw = $weight->adjbw($pbw, $weightKg);
        $dich  = $fluid->maintenanceFluid($weightKg);
        [$vtMin, $vtMax] = $vent->vtRange($pbw, $scenario);
        $rr    = $vent->rr($scenario);
        $peep  = $vent->peep($scenario);
        $fio2  = $vent->fio2($scenario);

        // =========================================================
        // IN BẢNG SO SÁNH RA TERMINAL (để bạn nhìn)
        // =========================================================
        fwrite(STDOUT, "\n");
        fwrite(STDOUT, "================================================\n");
        fwrite(STDOUT, "  SO SANH CODE vs EXCEL - Ca vang\n");
        fwrite(STDOUT, "  Nu, 80t, 150cm, 65kg, ASA II, routine\n");
        fwrite(STDOUT, "================================================\n");
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "Chi so", "Excel", "Code", "Ket qua"));
        fwrite(STDOUT, "------------------------------------------------\n");
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "BMI", $excelExpected['BMI'], $bmi, abs($bmi - $excelExpected['BMI']) < 0.2 ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "PBW", $excelExpected['PBW'], $pbw, abs($pbw - $excelExpected['PBW']) < 0.2 ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "AdjBW", $excelExpected['AdjBW'], $adjbw, abs($adjbw - $excelExpected['AdjBW']) < 0.6 ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "Dich", $excelExpected['Dich'], $dich, $dich == $excelExpected['Dich'] ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "Vt_min", $excelExpected['Vt_min'], $vtMin, abs($vtMin - $excelExpected['Vt_min']) < 6 ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "Vt_max", $excelExpected['Vt_max'], $vtMax, abs($vtMax - $excelExpected['Vt_max']) < 6 ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "RR", $excelExpected['RR'], $rr, $rr == $excelExpected['RR'] ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "PEEP", $excelExpected['PEEP'], $peep, $peep == $excelExpected['PEEP'] ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, sprintf("  %-10s | %-10s | %-10s | %s\n", "FiO2", $excelExpected['FiO2'], $fio2, $fio2 == $excelExpected['FiO2'] ? "KHOP ✓" : "LECH ✗"));
        fwrite(STDOUT, "================================================\n");
        fwrite(STDOUT, "  Mo Excel: docs/clinical/excel-original/Dr.Tom.xlsx\n");
        fwrite(STDOUT, "  Sheet Nhap_lieu dong DEMO-001, sheet Cong_thuc, May_tho\n");
        fwrite(STDOUT, "================================================\n\n");

        // =========================================================
        // ASSERT - Nếu lệch thì test sẽ báo FAIL
        // =========================================================
        $this->assertEqualsWithDelta($excelExpected['BMI'], $bmi, 0.1, 'BMI lệch so với Excel');
        $this->assertEqualsWithDelta($excelExpected['PBW'], $pbw, 0.1, 'PBW lệch so với Excel');
        $this->assertEqualsWithDelta($excelExpected['AdjBW'], $adjbw, 0.5, 'AdjBW lệch so với Excel');
        $this->assertEquals($excelExpected['Dich'], $dich, 'Dịch lệch so với Excel');
        $this->assertEqualsWithDelta($excelExpected['Vt_min'], $vtMin, 5, 'Vt_min lệch');
        $this->assertEqualsWithDelta($excelExpected['Vt_max'], $vtMax, 5, 'Vt_max lệch');
        $this->assertEquals($excelExpected['RR'], $rr);
        $this->assertEquals($excelExpected['PEEP'], $peep);
        $this->assertEquals($excelExpected['FiO2'], $fio2);
    }
}
