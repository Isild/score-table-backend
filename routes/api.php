<?php

use App\Http\Controllers\TeamController;
use App\Http\Requests\TeamDeleteRequest;
use App\Http\Requests\TeamGetIndexRequest;
use App\Http\Requests\TeamGetShowRequest;
use App\Http\Requests\TeamPostRequest;
use App\Http\Requests\TeamPutRequest;
use App\Http\Requests\Matches\MatchGetRequest;
use App\Http\Requests\Matches\MatchPostStartRequest;
use App\Http\Requests\Matches\MatchPostStopRequest;
use App\Http\Requests\Matches\MatchPatchScoreRequest;
use App\Models\FootballMatch;
use App\Models\Team;
use App\Repositories\TeamRepository;
use App\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('teams')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('teams.index');
    Route::post('/', [TeamController::class, 'post'])->name('teams.create');
    Route::get('/{model}', [TeamController::class, 'show'])->name('teams.show');
    Route::put('/{model}', [TeamController::class, 'put'])->name('teams.update');
    Route::delete('/{model}', [TeamController::class, 'delete'])->name('teams.delete');
});

Route::prefix('matches')->group(function () {
    Route::get('/', function (MatchGetRequest $request) {
        return FootballMatch::with('homeTeam', 'awayTeam')
            ->where('total_time', '=', '00:00:00')
            ->paginate($request->validated()['limit'] ?? 50);
    })->name('matches.active_matches');
    Route::get('/summary', function (MatchGetRequest $request) {
        return FootballMatch::with('homeTeam', 'awayTeam')
            ->where('total_time', '!=', '00:00:00')
            ->orderBy('total_match_score', 'desc')
            ->orderBy('created_at')
            ->paginate($request->validated()['limit'] ?? 50);
    })->name('matches.summary');
    Route::post('/start', function (MatchPostStartRequest $request) {
        $data = array_merge($request->validated(), [
            'home_team_score' => 0,
            'away_team_score' => 0,
            'total_match_score' => 0,
            'match_date' => Carbon::now()->toDateTimeString(),
        ]);

        return FootballMatch::class::create($data);
    })->name('matches.start');
    Route::post('/{footballMatch}/stop', function (MatchPostStopRequest $request, FootballMatch $footballMatch) {
        $footballMatch->total_match_score = $footballMatch->home_team_score + $footballMatch->away_team_score;
        $footballMatch->total_time = Carbon::now()->diff($footballMatch->created_at)->format('%H:%I:%S');
        $footballMatch->save();

        return $footballMatch;
    })->name('matches.stop');
    Route::patch('/{footballMatch}/score', function (MatchPatchScoreRequest $request, FootballMatch $footballMatch) {
        if ($footballMatch->total_time == '00:00:00') {
            $footballMatch->home_team_score = $request->validated()['home_team_score'];
            $footballMatch->away_team_score = $request->validated()['away_team_score'];
            $footballMatch->save();
        } else {
            return 403;
        }

        return $footballMatch;
    })->name('matches.update-score');
});