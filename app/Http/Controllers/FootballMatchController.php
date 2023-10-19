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
        return $this->match::class::where('total_time', '=', '00:00:00')
            ->with('homeTeam', 'awayTeam')
            ->paginate($request->validated()['limit'] ?? 50);
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
        return $this->match::class::with('homeTeam', 'awayTeam')
            ->where('total_time', '!=', '00:00:00')
            ->orderBy('total_match_score', 'desc')
            ->orderBy('created_at')
            ->paginate($request->validated()['limit'] ?? 50);
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
        $data = array_merge($request->validated(), [
            'home_team_score' => 0,
            'away_team_score' => 0,
            'total_match_score' => 0,
            'match_date' => Carbon::now()->toDateTimeString(),
        ]);

        $footballMatch = $this->match::class::create($data);

        return response()->json(
            $footballMatch,
            201
        );
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
        $footballMatch->total_match_score = $footballMatch->home_team_score + $footballMatch->away_team_score;
        $footballMatch->total_time = Carbon::now()->diff($footballMatch->created_at)->format('%H:%I:%S');
        $footballMatch->save();

        return response()->json(
            $footballMatch,
            200
        );
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
        if ($footballMatch->total_time == '00:00:00') {
            $requestData = $request->validated();
            $footballMatch->home_team_score = $requestData['home_team_score'];
            $footballMatch->away_team_score = $requestData['away_team_score'];
            $footballMatch->save();

            return response()->json(
                $footballMatch,
                200
            );
        } else {
            return response()->json(
                'Forbidden',
                403
            );
        }
    }

}