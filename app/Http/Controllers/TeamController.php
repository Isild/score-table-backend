<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamDeleteRequest;
use App\Http\Requests\TeamGetIndexRequest;
use App\Http\Requests\TeamGetShowRequest;
use App\Http\Requests\TeamPostRequest;
use App\Http\Requests\TeamPutRequest;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    public function __construct(protected TeamService $service)
    {
        //
    }

    /**
     * Index function show resource records in pagination.
     * 
     * @param TeamGetIndexRequest $request
     * 
     * @return LengthAwarePaginator
     */
    public function index(TeamGetIndexRequest $request): LengthAwarePaginator
    {
        return $this->service->getAll($request->validated());
    }

    /**
     * Show function return resource record.
     * 
     * @param TeamGetShowRequest $request
     * @param Team $model
     * 
     * @return JsonResponse
     */
    public function show(TeamGetShowRequest $request, Team $model): JsonResponse
    {
        return response()->json(
            $model,
            200
        );
    }

    /**
     * Post function create resource record in databse base on request data.
     * 
     * @param TeamPostRequest $request
     * 
     * @return JsonResponse
     */
    public function post(TeamPostRequest $request): JsonResponse
    {
        $team = $this->service->create($request->validated());

        return response()->json(
            $team,
            201
        );
    }

    /**
     * Put function update resource record data from database base on request data.
     * 
     * @param TeamPutRequest $request
     * @param Team $model
     * 
     * @return JsonResponse
     */
    public function put(TeamPutRequest $request, Team $model): JsonResponse
    {
        $team = $this->service->update($model, $request->validated());

        return response()->json(
            $team,
            200
        );
    }

    /**
     * Delete function delete resource record from database.
     * 
     * @param Request $request
     * @param Team $model
     * 
     * @return JsonResponse
     */
    public function delete(TeamDeleteRequest $request, Team $model): JsonResponse
    {
        $this->service->delete($model);

        return response()->json(
            null,
            204
        );
    }
}