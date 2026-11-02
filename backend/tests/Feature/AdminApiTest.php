<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function doctor(): User
    {
        return User::factory()->create(['role' => 'doctor']);
    }

    public function test_doctor_cannot_access_admin_users(): void
    {
        Sanctum::actingAs($this->doctor());
        $response = $this->getJson('/api/admin/users');

        $response->assertForbidden();
    }

    public function test_admin_can_create_and_deactivate_user(): void
    {
        Sanctum::actingAs($this->admin());
        $response = $this->postJson('/api/admin/users', [
            'name' => 'New Doctor',
            'email' => 'newdoc@gmhs.test',
            'password' => 'password123',
            'role' => 'doctor',
        ]);

        $response->assertCreated();
        $id = $response->json('id');

        $this->putJson("/api/admin/users/{$id}", [
            'is_active' => false,
        ])->assertOk();

        $this->assertDatabaseHas('users', ['id' => $id, 'is_active' => false]);
    }

    public function test_admin_cannot_delete_self_or_last_admin(): void
    {
        $admin = $this->admin();
        Sanctum::actingAs($admin);

        $this->deleteJson("/api/admin/users/{$admin->id}")
            ->assertStatus(422);
    }

    public function test_admin_can_toggle_drug_active(): void
    {
        $cat = \App\Models\DrugCategory::create(['name' => 'Test', 'slug' => 'test']);
        $drug = \App\Models\Drug::create([
            'drug_category_id' => $cat->id,
            'name' => 'TestDrug',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin());
        $this->putJson("/api/admin/drugs/{$drug->id}", [
            'is_active' => false,
        ])->assertOk();

        $this->assertDatabaseHas('drugs', ['id' => $drug->id, 'is_active' => false]);
    }
}
