<?php

namespace Tests\Feature\Matches;

use App\Models\FootballMatch;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Str;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $validPayloadStart = [];

    protected $invalidPayloadStart = [];

    protected Team $homeTeam;
    protected Team $awayTeam;

    protected function setUp(): void
    {
        parent::setUp();

        $this->homeTeam = Team::factory()->create();
        $this->awayTeam = Team::factory()->create();

        $this->validPayloadStart = [
            'home_team_id' => $this->homeTeam->id,
            'away_team_id' => $this->awayTeam->id,
        ];

        $this->invalidPayloadStart = [
            'home_team_id' => [
                -1,
                false,
                true,
                null,
                "a",
                [],
            ],
            'away_team_id' => [
                -1,
                false,
                true,
                null,
                "a",
                [],
            ],
        ];
    }

    public function test_start_post(): void
    {
        $this->assertDatabaseMissing('football_matches', $this->validPayloadStart);

        $response = $this->post(route('matches.start'), $this->validPayloadStart);

        $response->assertStatus(201);
        $this->assertDatabaseHas('football_matches', $this->validPayloadStart);
    }

    public function test_start_invalid_input(): void
    {
        foreach ($this->invalidPayloadStart as $key => $values) {
            foreach ($values as $value) {
                $response = $this->post(route('matches.start'), [
                    $key => $value,
                ]);

                $response->assertStatus(422);
                $this->assertArrayHasKey('message', json_decode($response->getContent(), true));
            }
        }
    }

    public function test_stop_post(): void
    {
        $match = FootballMatch::factory()->create([
            'home_team_score' => 0,
            'away_team_score' => 0,
            'total_match_score' => 0,
        ]);
        $this->travel(1)->hours();

        $response = $this->post(route('matches.stop', $match), $this->validPayloadStart);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('football_matches', $match->toArray());
        $this->assertDatabaseHas('football_matches', ['total_time' => json_decode($response->getContent(), true)['total_time']]);
    }
}