<?php

namespace Tests\Feature\Teams;

use App\Models\Team;
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

    public function test_index(): void
    {
        $teams = Team::factory(10)->create();

        $response = $this->get(route('teams.index'));
        $responseData = json_decode($response->getContent(), true)['data'];

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');

        foreach ($responseData as $team) {
            $foundTeam = $teams->where('id', '=', $team['id'])->first();
            $this->assertNotNull($foundTeam);
            $this->assertEquals($foundTeam->toArray(), $team);
        }
    }


    public function test_index_structure(): void
    {
        $teams = Team::factory(10)->create();

        $response = $this->get(route('teams.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                [
                    'id',
                    'name',
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

    public function test_index_query_limit(): void
    {
        $limit = 1;
        $teams = Team::factory(10)->create();

        $response = $this->call(
            'GET',
            route('teams.index'),
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

        $foundTeam = $teams->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundTeam);
        $this->assertEquals($foundTeam->toArray(), $responseData[0]);
    }

    public function test_index_query_page(): void
    {
        $limit = 1;
        $teams = Team::factory(10)->create();

        // first page
        $response = $this->call(
            'GET',
            route('teams.index'),
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

        $foundTeam = $teams->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundTeam);
        $this->assertEquals($foundTeam, $teams->first());
        $this->assertEquals($foundTeam->toArray(), $responseData[0]);

        // last page
        $response = $this->call(
            'GET',
            route('teams.index'),
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

        $foundTeam = $teams->where('id', '=', $responseData[0]['id'])->first();
        $this->assertNotNull($foundTeam);
        $this->assertEquals($foundTeam, $teams->last());
        $this->assertEquals($foundTeam->toArray(), $responseData[0]);
    }

    public function test_show()
    {
        $model = Team::factory()->create();

        $response = $this->get(route('teams.show', $model));
        $responseData = json_decode($response->getContent(), true);

        $response->assertStatus(200);
        $this->assertEquals($model->toArray(), $responseData);
    }

    public function test_show_not_found(): void
    {
        $response = $this->get(route('teams.index') . '/9999');

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