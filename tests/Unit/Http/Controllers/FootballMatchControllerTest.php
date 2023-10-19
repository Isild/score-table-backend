<?php

namespace Tests\Unit;

use App\Http\Controllers\FootballMatchController;
use App\Models\FootballMatch;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use App\Http\Requests\Matches\MatchGetRequest;
use App\Http\Requests\Matches\MatchPostStartRequest;
use App\Http\Requests\Matches\MatchPostStopRequest;
use App\Http\Requests\Matches\MatchPatchScoreRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class FootballMatchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_matches()
    {
        $request = $this->mock(MatchGetRequest::class, static function (MockInterface $mock) {
            $mock->shouldReceive('validated')
                ->with()
                ->once()
                ->andReturn([]);
        });

        $page = new LengthAwarePaginator([], 1, 1);
        $model = Mockery::mock(FootballMatch::class);
        $model->shouldReceive('where')
            ->with('total_time', '=', '00:00:00')
            ->once()
            ->andReturn($model);
        $model->shouldReceive('with')
            ->with('homeTeam', 'awayTeam')
            ->once()
            ->andReturn($model);
        $model->shouldReceive('paginate')
            ->once()
            ->andReturn($page);

        $controller = new FootballMatchController($model);
        $resp = $controller->active_matches($request);

        $this->assertEquals($page, $resp);
    }

    public function test_summary_matches()
    {
        $request = $this->mock(MatchGetRequest::class, static function (MockInterface $mock) {
            $mock->shouldReceive('validated')
                ->with()
                ->once()
                ->andReturn([]);
        });

        $page = new LengthAwarePaginator([], 1, 1);
        $model = Mockery::mock(FootballMatch::class);
        $model->shouldReceive('where')
            ->with('total_time', '!=', '00:00:00')
            ->once()
            ->andReturn($model);
        $model->shouldReceive('with')
            ->with('homeTeam', 'awayTeam')
            ->once()
            ->andReturn($model);
        $model->shouldReceive('orderBy')
            ->with('total_match_score', 'desc')
            ->once()
            ->andReturn($model);
        $model->shouldReceive('orderBy')
            ->with('created_at')
            ->once()
            ->andReturn($model);
        $model->shouldReceive('paginate')
            ->once()
            ->andReturn($page);

        $controller = new FootballMatchController($model);
        $resp = $controller->summary_matches($request);

        $this->assertEquals($page, $resp);
    }

    public function test_start_match()
    {
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $data = [
            'name' => 'name'
        ];
        $modelInstance = FootballMatch::factory()->make($data);

        $request = $this->mock(MatchPostStartRequest::class, static function (MockInterface $mock) {
            $mock->shouldReceive('validated')
                ->with()
                ->once()
                ->andReturn([]);
        });
        $model = Mockery::mock(FootballMatch::class);
        $model->shouldReceive('create')
            ->with([
                'home_team_score' => 0,
                'away_team_score' => 0,
                'total_match_score' => 0,
                'match_date' => Carbon::now()->toDateTimeString(),
            ])
            ->once()
            ->andReturn($modelInstance);

        $controller = new FootballMatchController($model);
        $resp = $controller->start_match($request);

        $this->assertEquals($modelInstance->toArray(), json_decode($resp->getContent(), true));
    }
    
    public function test_stop_match()
    {
        $data = [
            'total_time' => '00:00:00',
            'total_match_score' => 0,
            'home_team_score' => 1,
            'away_team_score' => 2,
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
        $modelInstance = FootballMatch::factory()->make($data);

        $now = Carbon::now()->addHour();
        Carbon::setTestNow($now);
        $model = Mockery::mock(FootballMatch::class);
        $request = $this->mock(MatchPostStopRequest::class, static function (MockInterface $mock) {
            //
        });

        $controller = new FootballMatchController($model);
        $resData = $controller->stop_match($request, $modelInstance);

        $resData = json_decode($resData->getContent(), true);
        $this->assertEquals(3, $resData['total_match_score']);
        $this->assertNotEquals('00:00:00', $resData['total_time']);
    }
    
    public function test_update_match_score()
    {
        $data = [
            'total_time' => '00:00:00',
            'total_match_score' => 0,
            'home_team_score' => 0,
            'away_team_score' => 0,
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
        $modelInstance = FootballMatch::factory()->make($data);

        $now = Carbon::now()->addHour();
        Carbon::setTestNow($now);
        $model = Mockery::mock(FootballMatch::class);
        $request = $this->mock(MatchPatchScoreRequest::class, static function (MockInterface $mock) {
            $mock->shouldReceive('validated')
                ->with()
                ->once()
                ->andReturn([
                    'home_team_score' => 1,
                    'away_team_score' => 2,
                ]);
        });

        $controller = new FootballMatchController($model);
        $resData = $controller->update_match_score($request, $modelInstance);

        $resData = json_decode($resData->getContent(), true);
        $this->assertEquals(1, $resData['home_team_score']);
        $this->assertEquals(2, $resData['away_team_score']);
    }
}