<?php

namespace Tests\Feature;

use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SourceApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_sources_ordered_by_priority(): void
    {
        Source::create(['name' => 'Beta', 'priority' => 'B']);
        Source::create(['name' => 'Alpha', 'priority' => 'A']);

        Sanctum::actingAs(User::factory()->create());
        $names = collect($this->getJson('/api/sources')->json())->pluck('name')->all();

        $this->assertSame(['Alpha', 'Beta'], $names);
    }

    public function test_guest_cannot_list_sources(): void
    {
        $this->getJson('/api/sources')->assertUnauthorized();
    }
}
