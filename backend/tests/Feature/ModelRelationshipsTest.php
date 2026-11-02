<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\DrugCategory;
use App\Models\Source;
use App\Models\VentilatorPreset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_source_can_be_linked_to_a_drug_and_a_ventilator_preset(): void
    {
        $category = DrugCategory::create([
            'name' => 'Nhóm thử nghiệm',
            'slug' => 'test-category',
        ]);

        $drug = Drug::create([
            'drug_category_id' => $category->id,
            'name' => 'Thuốc thử nghiệm',
        ]);

        $preset = VentilatorPreset::create([
            'clinical_scenario' => 'routine',
            'parameter' => 'Vt',
        ]);

        $source = Source::create([
            'name' => 'Tài liệu thử nghiệm',
        ]);

        $drug->sources()->attach($source->id);
        $preset->sources()->attach($source->id);

        $this->assertEquals($source->id, $drug->sources->first()->id);
        $this->assertEquals($source->id, $preset->sources->first()->id);
        $this->assertEquals($drug->id, $source->drugs->first()->id);
        $this->assertEquals($preset->id, $source->ventilatorPresets->first()->id);
    }
}
