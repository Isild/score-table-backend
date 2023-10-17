<?php

namespace Tests\Feature\Teams;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PutTest extends TestCase
{
    use RefreshDatabase;

    protected $validPayload = [
        'name' => 'nameU',
    ];

    protected $dataToCreate = [
        'name' => 'name',
    ];

    public function test_put(): void
    {
        $model = Team::factory()->create($this->dataToCreate);
        $this->assertDatabaseHas('teams', $this->dataToCreate);

        $response = $this->put(route('teams.update', $model), $this->validPayload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', $this->validPayload);
        $this->assertDatabaseMissing('teams', $this->dataToCreate);
    }
}