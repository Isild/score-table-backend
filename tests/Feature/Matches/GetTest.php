<?php

namespace Tests\Feature\Matches;

use App\Models\FootballMatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTest extends TestCase
{
    use RefreshDatabase;

    protected $invalidPayload = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->invalidPayload = [
            'limit' => [
                1,
                false,
                true,
                null,
                "a",
            ],
            'page' => [
                1,
                false,
                true,
                null,
                "a",
            ],
        ];
    }

    public function test_active_matches(): void
    {
        FootballMatch::factory(10)->create();
        $matches = FootballMatch::factory(10)->create([
            'total_time' => '00:00:00'
        ]);

        $response = $this->get(route('matches.active_matches'));
        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');

        foreach ($responseData as $match) {
            $foundMatch = $matches->where('id', '=', $match['id'])->first();

            $this->assertNotNull($foundMatch);
            $this->assertEquals($foundMatch->toArray()['id'], $match['id']);
            $this->assertEquals($foundMatch->toArray()['home_team_id'], $match['home_team_id']);
            $this->assertEquals($foundMatch->toArray()['away_team_id'], $match['away_team_id']);
            $this->assertEquals($foundMatch->toArray()['home_team_score'], $match['home_team_score']);
            $this->assertEquals($foundMatch->toArray()['away_team_score'], $match['away_team_score']);
        }
    }

    public function test_active_matches_structure(): void
    {
        $matches = FootballMatch::factory(10)->create([
            'total_time' => '00:00:00'
        ]);

        $response = $this->get(route('matches.active_matches'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                [
                    'id',
                    'home_team_id',
                    'away_team_id',
                    'home_team_score',
                    'away_team_score',
                    'total_match_score',
                    'match_date',
                    'total_time',
                    'home_team' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'away_team' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'created_at',
                    'updated_at',
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links' => [
                [
                    'url',
                    'label',
                    'active',
                ]
            ],
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    public function test_active_matches_limit(): void
    {
        $limit = 1;
        $matches = FootballMatch::factory(10)->create([
            'total_time' => '00:00:00'
        ]);

        $response = $this->call(
            'GET',
            route('matches.active_matches'),
            [
                'limit' => $limit,
            ],
            [],
            [],
            []
        );

        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');

        $foundMatch = $matches->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundMatch);
        $this->assertEquals($foundMatch->toArray()['id'], $responseData[0]['id']);
        $this->assertEquals($foundMatch->toArray()['home_team_id'], $responseData[0]['home_team_id']);
        $this->assertEquals($foundMatch->toArray()['away_team_id'], $responseData[0]['away_team_id']);
        $this->assertEquals($foundMatch->toArray()['home_team_score'], $responseData[0]['home_team_score']);
        $this->assertEquals($foundMatch->toArray()['away_team_score'], $responseData[0]['away_team_score']);
    }

    public function test_active_matches_query_page(): void
    {
        $limit = 1;
        $matches = FootballMatch::factory(10)->create([
            'total_time' => '00:00:00'
        ]);

        // first page
        $response = $this->call(
            'GET',
            route('matches.active_matches'),
            [
                'limit' => $limit,
                'page' => 1,
            ],
            [],
            [],
            []
        );

        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');

        $foundMatch = $matches->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundMatch);
        $this->assertEquals($foundMatch, $matches->first());
        $this->assertEquals($foundMatch->toArray()['id'], $responseData[0]['id']);
        $this->assertEquals($foundMatch->toArray()['home_team_id'], $responseData[0]['home_team_id']);
        $this->assertEquals($foundMatch->toArray()['away_team_id'], $responseData[0]['away_team_id']);
        $this->assertEquals($foundMatch->toArray()['home_team_score'], $responseData[0]['home_team_score']);
        $this->assertEquals($foundMatch->toArray()['away_team_score'], $responseData[0]['away_team_score']);

        // last page
        $response = $this->call(
            'GET',
            route('matches.active_matches'),
            [
                'limit' => $limit,
                'page' => 10,
            ],
            [],
            [],
            []
        );

        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');

        $foundMatch = $matches->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundMatch);
        $this->assertEquals($foundMatch, $matches->last());
        $this->assertEquals($foundMatch->toArray()['id'], $responseData[0]['id']);
        $this->assertEquals($foundMatch->toArray()['home_team_id'], $responseData[0]['home_team_id']);
        $this->assertEquals($foundMatch->toArray()['away_team_id'], $responseData[0]['away_team_id']);
        $this->assertEquals($foundMatch->toArray()['home_team_score'], $responseData[0]['home_team_score']);
        $this->assertEquals($foundMatch->toArray()['away_team_score'], $responseData[0]['away_team_score']);
    }

    public function test_summary_matches(): void
    {
        $matches = FootballMatch::factory(10)->create();
        FootballMatch::factory(10)->create([
            'total_time' => '00:00:00'
        ]);

        $response = $this->get(route('matches.summary'));
        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');

        foreach ($responseData as $match) {
            $foundMatch = $matches->where('id', '=', $match['id'])->first();

            $this->assertNotNull($foundMatch);
            $this->assertEquals($foundMatch->toArray()['id'], $match['id']);
            $this->assertEquals($foundMatch->toArray()['home_team_id'], $match['home_team_id']);
            $this->assertEquals($foundMatch->toArray()['away_team_id'], $match['away_team_id']);
            $this->assertEquals($foundMatch->toArray()['home_team_score'], $match['home_team_score']);
            $this->assertEquals($foundMatch->toArray()['away_team_score'], $match['away_team_score']);
        }
    }

    public function test_summary_matches_structure(): void
    {
        $matches = FootballMatch::factory(10)->create();

        $response = $this->get(route('matches.summary'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                [
                    'id',
                    'home_team_id',
                    'away_team_id',
                    'home_team_score',
                    'away_team_score',
                    'total_match_score',
                    'match_date',
                    'total_time',
                    'home_team' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'away_team' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                    ],
                    'created_at',
                    'updated_at',
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links' => [
                [
                    'url',
                    'label',
                    'active',
                ]
            ],
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    public function test_summary_matches_limit(): void
    {
        $limit = 1;
        $matches = FootballMatch::factory(10)->create();

        $response = $this->call(
            'GET',
            route('matches.summary'),
            [
                'limit' => $limit,
            ],
            [],
            [],
            []
        );

        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');

        $foundMatch = $matches->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundMatch);
        $this->assertEquals($foundMatch->toArray()['id'], $responseData[0]['id']);
        $this->assertEquals($foundMatch->toArray()['home_team_id'], $responseData[0]['home_team_id']);
        $this->assertEquals($foundMatch->toArray()['away_team_id'], $responseData[0]['away_team_id']);
        $this->assertEquals($foundMatch->toArray()['home_team_score'], $responseData[0]['home_team_score']);
        $this->assertEquals($foundMatch->toArray()['away_team_score'], $responseData[0]['away_team_score']);
    }

    public function test_summary_matches_query_page(): void
    {
        $limit = 1;
        $matches = FootballMatch::factory(10)->create();
        $matches = $matches->sortByDesc('total_match_score');
        $matches = $matches->sortBy('created_at');

        // first page
        $response = $this->call(
            'GET',
            route('matches.summary'),
            [
                'limit' => $limit,
                'page' => 1,
            ],
            [],
            [],
            []
        );

        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');

        $foundMatch = $matches->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundMatch);
        $this->assertEquals($foundMatch, $matches->first());
        $this->assertEquals($foundMatch->toArray()['id'], $responseData[0]['id']);
        $this->assertEquals($foundMatch->toArray()['home_team_id'], $responseData[0]['home_team_id']);
        $this->assertEquals($foundMatch->toArray()['away_team_id'], $responseData[0]['away_team_id']);
        $this->assertEquals($foundMatch->toArray()['home_team_score'], $responseData[0]['home_team_score']);
        $this->assertEquals($foundMatch->toArray()['away_team_score'], $responseData[0]['away_team_score']);

        // last page
        $response = $this->call(
            'GET',
            route('matches.summary'),
            [
                'limit' => $limit,
                'page' => 10,
            ],
            [],
            [],
            []
        );

        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');

        $foundMatch = $matches->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundMatch);
        $this->assertEquals($foundMatch, $matches->last());
        $this->assertEquals($foundMatch->toArray()['id'], $responseData[0]['id']);
        $this->assertEquals($foundMatch->toArray()['home_team_id'], $responseData[0]['home_team_id']);
        $this->assertEquals($foundMatch->toArray()['away_team_id'], $responseData[0]['away_team_id']);
        $this->assertEquals($foundMatch->toArray()['home_team_score'], $responseData[0]['home_team_score']);
        $this->assertEquals($foundMatch->toArray()['away_team_score'], $responseData[0]['away_team_score']);
    }
}