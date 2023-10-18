<?php

use App\Http\Controllers\TeamController;
use App\Http\Requests\TeamDeleteRequest;
use App\Http\Requests\TeamGetIndexRequest;
use App\Http\Requests\TeamGetShowRequest;
use App\Http\Requests\TeamPostRequest;
use App\Http\Requests\TeamPutRequest;
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
    Route::get('/', function (Request $request) {
        //
    })->name('matches.active_matches');
    Route::get('/summary', function (Request $request) {
        //
    })->name('matches.summary');
    Route::post('/start', function (Request $request) {
        //
    })->name('matches.start');
    Route::post('/{footballMatch}/stop', function (Request $request, FootballMatch $footballMatch) {
        //
    })->name('matches.stop');
    Route::patch('/{footballMatch}/score', function (Request $request, FootballMatch $footballMatch) {
        //
    })->name('matches.update-score');
});