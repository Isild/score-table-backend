<?php

use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::get('/', function (Request $request) {
        $repo = new TeamRepository(new Team());

        return $repo->getAll($request->all());
    })->name('teams.index');
    Route::post('/', function (Request $request) {
        $repo = new TeamRepository(new Team());
        $team = $repo->create($request->all());

        return $team;
    })->name('teams.create');
    Route::get('/{team}', function (Request $request, Team $team) {
        return $team;
    })->name('teams.show');
    Route::put('/{team}', function (Request $request, Team $team) {
        $repo = new TeamRepository(new Team());
        $repo->update($team, $request->all());

        return $team;
    })->name('teams.update');
    Route::delete('/{team}', function (Request $request, Team $team) {
        $repo = new TeamRepository(new Team());
        $repo->delete($team);

        return true;
    })->name('teams.delete');
});