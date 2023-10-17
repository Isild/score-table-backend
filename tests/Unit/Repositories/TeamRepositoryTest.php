<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class TeamRepositoryTest extends TestCase
{
    public function test_get_all()
    {
        $page = new LengthAwarePaginator([], 1, 1);
        $model = Mockery::mock(Team::class);
        $model->shouldReceive('paginate')
            ->once()
            ->andReturn($page);

        $repository = new TeamRepository($model);
        $resp = $repository->getAll([]);

        $this->assertEquals($page, $resp);
    }

    public function test_get_by_id()
    {
        $modelInstance = Team::factory()->make();
        $modelInstance->id = 1;
        $modelCollection = new Collection([$modelInstance]);

        $model = Mockery::mock(Team::class);
        $model->shouldReceive('where')
            ->with('id', '=', $modelInstance->id)
            ->once()
            ->andReturn($modelCollection);

        $repository = new TeamRepository($model);
        $resp = $repository->getById($modelInstance->id);

        $this->assertEquals($modelInstance, $resp);
    }

    public function test_create()
    {
        $data = [
            'name' => 'name'
        ];

        $modelInstance = Team::factory()->make($data);

        $model = Mockery::mock(Team::class);
        $model->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($modelInstance);

        $repository = new TeamRepository($model);
        $resp = $repository->create($data);

        $this->assertEquals($modelInstance, $resp);
    }

    public function test_update()
    {
        $data = [
            'name' => 'name'
        ];
        $dataU = [
            'name' => 'nameUpdated'
        ];
        $modelInstance = Team::factory()->make($data);

        $model = Mockery::mock(Team::class);

        $repository = new TeamRepository($model);
        $resp = $repository->update($modelInstance, $dataU);

        $modelData = $resp->attributesToArray();
        $this->assertEquals($dataU['name'], $modelData['name']);
        $this->assertNotEquals($data['name'], $modelData['name']);
    }
}