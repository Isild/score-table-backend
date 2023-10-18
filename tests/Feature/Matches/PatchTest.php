<?php

namespace Tests\Feature\Matches;

use App\Models\FootballMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatchTest extends TestCase
{
    use RefreshDatabase;

    protected $validPayload = [];

    protected $dataToCreate = [];

    protected $invalidPayload = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->validPayload = [
            'home_team_score' => 1,
            'away_team_score' => 2,
        ];

        $this->invalidPayload = [
            'home_team_score' => [
                -1,
                false,
                null,
                "a",
            ],
            'away_team_score' => [
                -1,
                false,
                null,
                "a",
            ],
        ];

        $this->dataToCreate = [
            'home_team_score' => 0,
            'away_team_score' => 0,
            'total_match_score' => 0,
            'total_time' => '00:00:00',
        ];
    }

    public function test_patch(): void
    {
        $model = FootballMatch::factory()->create($this->dataToCreate);
        $this->assertDatabaseHas('football_matches', $this->dataToCreate);

        $response = $this->patch(route('matches.update-score', $model), $this->validPayload);

        $response->assertStatus(200);
        $this->assertDatabaseHas('football_matches', $this->validPayload);
        $this->assertDatabaseMissing('football_matches', $this->dataToCreate);
    }

    public function test_patch_finished(): void
    {
        $this->dataToCreate = [];
        $model = FootballMatch::factory()->create($this->dataToCreate);
        $this->assertDatabaseHas('football_matches', $this->dataToCreate);

        $response = $this->patch(route('matches.update-score', $model), $this->validPayload);

        $response->assertStatus(403);
        $this->assertDatabaseHas('football_matches', $this->dataToCreate);
    }

    public function test_patch_not_found(): void
    {
        $response = $this->patch(route('matches.active_matches') . "/99999/score", $this->validPayload);

        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(404);
        $this->assertEquals('Resource not found.', $responseData['message']);
    }

    public function test_patch_invalid_input(): void
    {
        $model = FootballMatch::factory()->create($this->dataToCreate);
        foreach ($this->invalidPayload as $key => $values) {
            foreach ($values as $value) {
                $response = $this->patch(route('matches.update-score', $model), [
                    $key => $value,
                ]);

                $response->assertStatus(422);
            }
        }
    }
}