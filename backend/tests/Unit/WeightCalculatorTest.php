<?php

namespace Tests\Unit;

use App\Services\Calculators\WeightCalculator;
use PHPUnit\Framework\TestCase;

class WeightCalculatorTest extends TestCase
{
    private WeightCalculator $calc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calc = new WeightCalculator();
    }

    public function test_golden_case_female_80y_150cm_65kg(): void
    {
        $bmi = $this->calc->bmi(65, 150);
        $pbw = $this->calc->pbw('female', 150);
        $adjbw = $this->calc->adjbw($pbw, 65);

        $this->assertEqualsWithDelta(28.9, $bmi, 0.1);
        $this->assertEqualsWithDelta(43.3, $pbw, 0.1);
        $this->assertEqualsWithDelta(52.0, $adjbw, 0.5);
    }

    public function test_pbw_male(): void
    {
        $pbw = $this->calc->pbw('male', 170);
        $this->assertEqualsWithDelta(66.0, $pbw, 0.5);
    }
}
