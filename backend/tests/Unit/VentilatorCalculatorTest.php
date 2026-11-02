<?php

namespace Tests\Unit;

use App\Services\Calculators\VentilatorCalculator;
use App\Services\Calculators\WeightCalculator;
use PHPUnit\Framework\TestCase;

class VentilatorCalculatorTest extends TestCase
{
    public function test_golden_case_routine(): void
    {
        $weight = new WeightCalculator();
        $vent = new VentilatorCalculator();

        $pbw = $weight->pbw('female', 150); // 43.3
        [$vtMin, $vtMax] = $vent->vtRange($pbw, 'routine');

        $this->assertEqualsWithDelta(260, $vtMin, 5);
        $this->assertEqualsWithDelta(303, $vtMax, 5);
        $this->assertEquals(14, $vent->rr('routine'));
        $this->assertEquals(5, $vent->peep('routine'));
    }
}
