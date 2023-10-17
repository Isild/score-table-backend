<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Repositories\TeamRepository;
use App\Services\TeamService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TeamServiceTest extends TestCase
{
    public function test_get_all()
    {
        $page = new LengthAwarePaginator([], 1, 1);

        $repository = $this->mock(TeamRepository::class, function (MockInterface $mock) use ($page) {
            $mock->shouldReceive('getAll')
                ->with([])
                ->once()
                ->andReturn($page);
        });

        $service = new TeamService($repository);
        $resp = $service->getAll([]);

        $this->assertEquals($page, $resp);
    }

    public function test_get_by_id()
    {
        $modelInstance = Team::factory()->make();
        $modelInstance->id = 1;
        $modelCollection = new Collection([$modelInstance]);

        $repository = $this->mock(TeamRepository::class, function (MockInterface $mock) use ($modelInstance) {
            $mock->shouldReceive('getById')
                ->with($modelInstance->id)
                ->once()
                ->andReturn($modelInstance);
        });

        $service = new TeamService($repository);
        $resp = $service->getById($modelInstance->id);

        $this->assertEquals($modelInstance, $resp);
    }

    public function test_create()
    {
        $data = [
            'name' => 'name'
        ];
        $modelInstance = Team::factory()->make($data);

        $repository = $this->mock(TeamRepository::class, function (MockInterface $mock) use ($data, $modelInstance) {
            $mock->shouldReceive('create')
                ->with($data)
                ->once()
                ->andReturn($modelInstance);
        });

        $service = new TeamService($repository);
        $resp = $service->create($data);

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

        $repository = $this->mock(TeamRepository::class, function (MockInterface $mock) use ($dataU, $modelInstance) {
            $mock->shouldReceive('update')
                ->with($modelInstance, $dataU)
                ->once()
                ->andReturn($modelInstance);
        });

        $service = new TeamService($repository);
        $resp = $service->update($modelInstance, $dataU);

        $modelData = $resp->attributesToArray();
        $this->assertEquals($data['name'], $modelData['name']);
    }
}