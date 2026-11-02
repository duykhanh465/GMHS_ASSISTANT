<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\DrugCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DrugApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_active_drugs_and_hides_inactive(): void
    {
        $cat = DrugCategory::create(['name' => 'Opioid', 'slug' => 'opioid']);
        Drug::create(['drug_category_id' => $cat->id, 'name' => 'Fentanyl', 'is_active' => true]);
        Drug::create(['drug_category_id' => $cat->id, 'name' => 'Hidden', 'is_active' => false]);

        Sanctum::actingAs(User::factory()->create());
        $names = collect($this->getJson('/api/drugs')->json('data'))->pluck('name');

        $this->assertTrue($names->contains('Fentanyl'));
        $this->assertFalse($names->contains('Hidden'));
    }

    public function test_index_filters_by_category_slug(): void
    {
        $opioid = DrugCategory::create(['name' => 'Opioid', 'slug' => 'opioid']);
        $vaso = DrugCategory::create(['name' => 'Vaso', 'slug' => 'van-mach']);
        Drug::create(['drug_category_id' => $opioid->id, 'name' => 'Fentanyl', 'is_active' => true]);
        Drug::create(['drug_category_id' => $vaso->id, 'name' => 'Ephedrine', 'is_active' => true]);

        Sanctum::actingAs(User::factory()->create());
        $names = collect($this->getJson('/api/drugs?category=opioid')->json('data'))->pluck('name');

        $this->assertTrue($names->contains('Fentanyl'));
        $this->assertFalse($names->contains('Ephedrine'));
    }
}
