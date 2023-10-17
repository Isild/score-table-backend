<?php

namespace App\Interfaces;

use App\Repositories\AbstractRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;


interface ServiceInterface
{
    /**
     * Function to return collection of model data.
     * 
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters): LengthAwarePaginator;

    /**
     * Function return single model instance base on id.
     * 
     * @param int $id
     * 
     * @return Model
     */
    public function getById(int $id): Model;

    /**
     * Function create model instance base on date.
     * 
     * @param array $data
     * 
     * @return Model
     */
    public function create(array $data): Model;

    /**
     * Function update model instance base on date.
     * 
     * @param Model $model
     * @param array $data
     * 
     * @return Model
     */
    public function update(Model $model, array $data): Model;

    /**
     * Function remove model instance.
     * 
     * @param Model $model
     * 
     * @return bool
     */
    public function delete(Model $model): bool;
}