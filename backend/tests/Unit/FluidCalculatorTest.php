<?php

namespace Tests\Unit;

use App\Services\Calculators\FluidCalculator;
use PHPUnit\Framework\TestCase;

class FluidCalculatorTest extends TestCase
{
    public function test_golden_case_65kg(): void
    {
        $calc = new FluidCalculator();
        $this->assertEquals(105, $calc->maintenanceFluid(65));
        $this->assertEquals(40, $calc->maintenanceFluid(10));
        $this->assertEquals(60, $calc->maintenanceFluid(20));
    }
}
