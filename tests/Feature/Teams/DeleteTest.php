<?php

namespace Tests\Feature\Teams;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $dataToCreate = [
        'name' => 'name',
    ];

    public function test_delete(): void
    {
        $model = Team::factory()->create($this->dataToCreate);
        $this->assertDatabaseHas('teams', $model->toArray());

        $response = $this->delete(route('teams.delete', $model));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('teams', $model->toArray());
    }

    public function test_delete_not_found(): void
    {
        $response = $this->delete(route('teams.index') . "/99999");

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(404);
        $this->assertEquals('Resource not found.', $responseData['message']);
    }
}