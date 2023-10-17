<?php

namespace Tests\Feature\Teams;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $validPayload = [
        'name' => 'name',
    ];

    /**
     * Index test.
     *
     * @return void
     */
    public function test_post(): void
    {
        $this->assertDatabaseMissing('teams', $this->validPayload);

        $response = $this->post(route('teams.create'), $this->validPayload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('teams', $this->validPayload);
    }
}