<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\EmergencyProtocolSeeder;
use Database\Seeders\SpinalProtocolSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProtocolApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_spinal_protocols_seeded_and_localized(): void
    {
        (new SpinalProtocolSeeder())->run();
        $user = User::factory()->create(['role' => 'doctor']);

        \Laravel\Sanctum\Sanctum::actingAs($user);
        $response = $this->getJson('/api/spinal-protocols?locale=en');

        $response->assertOk();
        $this->assertCount(7, $response->json());
        $this->assertSame('Intrathecal bupivacaine', $response->json()[0]['technique']);
    }

    public function test_emergency_protocols_seeded_with_english(): void
    {
        (new EmergencyProtocolSeeder())->run();
        $user = User::factory()->create(['role' => 'doctor']);

        \Laravel\Sanctum\Sanctum::actingAs($user);
        $response = $this->getJson('/api/emergency-protocols?locale=en');

        $response->assertOk();
        $this->assertCount(7, $response->json());
        $this->assertSame('Anaphylaxis', $response->json()[3]['situation']);
    }

    public function test_drugs_localize_category_name(): void
    {
        $cat = \App\Models\DrugCategory::create([
            'name' => 'Thuốc khởi mê',
            'slug' => 'khoi-me',
            'translations' => ['en' => ['name' => 'Induction']],
        ]);
        \App\Models\Drug::create([
            'drug_category_id' => $cat->id,
            'name' => 'TestDrug',
            'is_active' => true,
        ]);
        $user = User::factory()->create(['role' => 'doctor']);

        \Laravel\Sanctum\Sanctum::actingAs($user);
        $response = $this->getJson('/api/drugs?locale=en');

        $response->assertOk();
        $this->assertSame('Induction', $response->json('data.0.category.name'));
    }
}
