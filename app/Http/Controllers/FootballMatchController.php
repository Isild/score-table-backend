<?php

namespace App\Http\Controllers;

use App\Http\Requests\Matches\MatchGetRequest;
use App\Http\Requests\Matches\MatchPostStartRequest;
use App\Http\Requests\Matches\MatchPostStopRequest;
use App\Http\Requests\Matches\MatchPatchScoreRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use App\Models\FootballMatch;
use Carbon\Carbon;

class FootballMatchController extends Controller
{
    public function __construct(protected FootballMatch $match)
    {
        //
    }

    /**
     * Function return all active matches.
     * 
     * @param MatchGetRequest $request
     * 
     * @return LengthAwarePaginator
     */
    public function active_matches(MatchGetRequest $request): LengthAwarePaginator
    {
        //
    }

    /**
     * Function return all summary matches in right order.
     * 
     * @param MatchGetRequest $request
     * 
     * @return LengthAwarePaginator
     */
    public function summary_matches(MatchGetRequest $request): LengthAwarePaginator
    {
        //
    }

    /**
     * Function start match.
     * 
     * @param MatchPostStartRequest $request
     * 
     * @return JsonResponse
     */
    public function start_match(MatchPostStartRequest $request): JsonResponse
    {
        //
    }

    /**
     * Function stop match.
     * 
     * @param MatchPostStopRequest $request
     * 
     * @return JsonResponse
     */
    public function stop_match(MatchPostStopRequest $request, FootballMatch $footballMatch): JsonResponse
    {
        //
    }

    /**
     * Function update match score.
     * 
     * @param MatchPatchScoreRequest $request
     * 
     * @return JsonResponse
     */
    public function update_match_score(MatchPatchScoreRequest $request, FootballMatch $footballMatch): JsonResponse
    {
        //
    }

}