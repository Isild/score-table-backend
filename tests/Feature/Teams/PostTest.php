<?php

namespace Tests\Feature\Teams;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Str;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $validPayload = [];

    protected $invalidPayload = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->validPayload = [
            'name' => 'name',
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
    }

    public function test_post(): void
    {
        $this->assertDatabaseMissing('teams', $this->validPayload);

        $response = $this->post(route('teams.create'), $this->validPayload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('teams', $this->validPayload);
    }

    public function test_invalid_input(): void
    {
        foreach ($this->invalidPayload as $key => $values) {
            foreach ($values as $value) {
                $response = $this->post(route('teams.create'), [
                    $key => $value,
                ]);

                $response->assertStatus(422);
                $this->assertArrayHasKey('message', json_decode($response->getContent(), true));
            }
        }
    }
}