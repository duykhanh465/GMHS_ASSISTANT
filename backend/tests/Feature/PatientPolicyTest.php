<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PatientPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makePatient(User $owner, string $code = 'DEMO-001'): Patient
    {
        return Patient::create([
            'user_id' => $owner->id,
            'code' => $code,
            'full_name' => 'Demo',
            'age' => 80,
            'gender' => 'female',
            'height_cm' => 150,
            'weight_kg' => 65,
            'asa_class' => 'II',
            'respiratory_condition' => 'routine',
        ]);
    }

    public function test_doctor_sees_own_patients_only(): void
    {
        $a = User::factory()->create(['role' => 'doctor']);
        $b = User::factory()->create(['role' => 'doctor']);
        $this->makePatient($a, 'A-001');
        $this->makePatient($b, 'B-001');

        Sanctum::actingAs($a);
        $codes = collect($this->getJson('/api/patients')->json('data'))->pluck('code');
        $this->assertTrue($codes->contains('A-001'));
        $this->assertFalse($codes->contains('B-001'));
    }

    public function test_doctor_cannot_view_another_doctors_patient(): void
    {
        $a = User::factory()->create(['role' => 'doctor']);
        $b = User::factory()->create(['role' => 'doctor']);
        $patient = $this->makePatient($a);

        Sanctum::actingAs($b);
        $this->getJson("/api/patients/{$patient->id}")->assertForbidden();
    }

    public function test_admin_can_view_any_patient(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $doctor = User::factory()->create(['role' => 'doctor']);
        $patient = $this->makePatient($doctor);

        Sanctum::actingAs($admin);
        $this->getJson("/api/patients/{$patient->id}")->assertOk();
    }
}
