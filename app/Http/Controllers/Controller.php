<?php

namespace App\Http\Controllers;

use App\Services\AbstractService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var AbstractService
     */
    protected AbstractService $service;

    /**
     * Index function show resource records in pagination.
     * 
     * @param Request $request
     * 
     * @return LengthAwarePaginator
     */
    abstract public function index(Request $request): LengthAwarePaginator;

    /**
     * Show function return resource record.
     * 
     * @param Request $request
     * @param Model $model
     * 
     * @return Response
     */
    abstract public function show(Request $request, Model $model): Response;

    /**
     * Post function create resource record in databse base on request data.
     * 
     * @param Request $request
     * 
     * @return Response
     */
    abstract public function post(Request $request): Response;

    /**
     * Put function update resource record data from database base on request data.
     * 
     * @param Request $request
     * @param Model $model
     * 
     * @return Response
     */
    abstract public function put(Request $request, Model $model): Response;

    /**
     * Delete function delete resource record from database.
     * 
     * @param Request $request
     * @param Model $model
     * 
     * @return Response
     */
    abstract public function delete(Request $request, Model $model): Response;
}