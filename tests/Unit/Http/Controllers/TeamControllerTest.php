<?php

namespace Tests\Unit;

use App\Http\Controllers\TeamController;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Http\Requests\TeamGetIndexRequest;
use App\Http\Requests\TeamGetShowRequest;
use App\Http\Requests\TeamPostRequest;
use App\Http\Requests\TeamPutRequest;
use App\Http\Requests\TeamDeleteRequest;

class TeamControllerTest extends TestCase
{
    public function test_index()
    {
        $page = new LengthAwarePaginator([], 1, 1);

        $service = $this->mock(TeamService::class, function (MockInterface $mock) use ($page) {
            $mock->shouldReceive('getAll')
                ->with([])
                ->once()
                ->andReturn($page);
        });
        $request = $this->mock(TeamGetIndexRequest::class, static function (MockInterface $mock) {
            $mock->shouldReceive('validated')
                ->with()
                ->once()
                ->andReturn([]);
        });

        $controller = new TeamController($service);
        $resp = $controller->index($request);

        $this->assertEquals($page, $resp);
    }

    public function test_show()
    {
        $modelInstance = Team::factory()->make();
        $modelInstance->id = 1;

        $service = $this->mock(TeamService::class, function (MockInterface $mock) use ($modelInstance) {
            //
        });
        $request = $this->mock(TeamGetShowRequest::class, static function (MockInterface $mock) {
            //
        });

        $controller = new TeamController($service);
        $resp = $controller->show($request, $modelInstance);
        $this->assertEquals($modelInstance->toArray(), json_decode($resp->getContent(), true));
    }

    public function test_post()
    {
        $data = [
            'name' => 'name'
        ];
        $modelInstance = Team::factory()->make($data);

        $service = $this->mock(TeamService::class, function (MockInterface $mock) use ($modelInstance) {
            $mock->shouldReceive('create')
                ->with([])
                ->once()
                ->andReturn($modelInstance);
        });
        $request = $this->mock(TeamPostRequest::class, static function (MockInterface $mock) {
            $mock->shouldReceive('validated')
                ->with()
                ->once()
                ->andReturn([]);
        });

        $controller = new TeamController($service);
        $resp = $controller->post($request);

        $this->assertEquals($modelInstance->toArray(), json_decode($resp->getContent(), true));
    }

    public function test_put()
    {
        $data = [
            'name' => 'name'
        ];
        $dataU = [
            'name' => 'nameUpdated'
        ];
        $modelInstance = Team::factory()->make($dataU);

        $service = $this->mock(TeamService::class, function (MockInterface $mock) use ($dataU, $modelInstance) {
            $mock->shouldReceive('update')
                ->with($modelInstance, $dataU)
                ->once()
                ->andReturn($modelInstance);
        });
        $request = $this->mock(TeamPutRequest::class, static function (MockInterface $mock) use ($dataU) {
            $mock->shouldReceive('validated')
                ->with()
                ->once()
                ->andReturn($dataU);
        });

        $controller = new TeamController($service);
        $resp = $controller->put($request, $modelInstance);

        $this->assertEquals($modelInstance->toArray(), json_decode($resp->getContent(), true));
    }

    public function test_delete()
    {
        $data = [
            'name' => 'name'
        ];
        $modelInstance = Team::factory()->make($data);

        $service = $this->mock(TeamService::class, function (MockInterface $mock) use ($modelInstance) {
            $mock->shouldReceive('delete')
                ->with($modelInstance)
                ->once()
                ->andReturn(true);
        });
        $request = $this->mock(TeamDeleteRequest::class, static function (MockInterface $mock) {
            //
        });

        $controller = new TeamController($service);
        $resp = $controller->delete($request, $modelInstance);

        $this->assertEquals([], json_decode($resp->getContent(), true));
    }
}