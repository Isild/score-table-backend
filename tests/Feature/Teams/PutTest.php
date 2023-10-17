<?php

namespace Tests\Feature\Teams;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PutTest extends TestCase
{
    use RefreshDatabase;

    protected $validPayload = [];

    protected $dataToCreate = [];

    protected $invalidPayload = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->validPayload = [
            'name' => 'nameU',
        ];

        $this->invalidPayload = [
            'name' => [
                1,
                false,
                true,
                null,
                "a",
                Str::random(501),
            ],
        ];

        $this->dataToCreate = [
            'name' => 'name',
        ];
    }

    public function test_put(): void
    {
        $model = Team::factory()->create($this->dataToCreate);
        $this->assertDatabaseHas('teams', $this->dataToCreate);

        $response = $this->put(route('teams.update', $model), $this->validPayload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', $this->validPayload);
        $this->assertDatabaseMissing('teams', $this->dataToCreate);
    }

    public function test_put_not_found(): void
    {
        $response = $this->put(route('teams.index') . "/99999", $this->validPayload);

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(404);
        $this->assertEquals('Resource not found.', $responseData['message']);
    }

    public function test_invalid_input(): void
    {
        foreach ($this->invalidPayload as $key => $values) {
            foreach ($values as $value) {
                $response = $this->post(route('teams.create'), [
                    $key => $value,
                ]);

                $response->assertStatus(422);
            }
        }
    }
}