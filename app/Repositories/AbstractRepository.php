<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


abstract class AbstractRepository
{
    /**
     * Model
     * 
     * @var Model
     */
    protected Model $model;

    /**
     * Function to return collection of model data.
     * 
     * @return Collection
     */
    abstract public function getAll(): Collection;

    /**
     * Function return single model instance base on id.
     * 
     * @param int $id
     * 
     * @return Model
     */
    abstract public function getById(int $id): Model;

    /**
     * Function create model instance base on date.
     * 
     * @param array $data
     * 
     * @return Model
     */
    abstract public function create(array $data): Model;

    /**
     * Function update model instance base on date.
     * 
     * @param Model $model
     * @param array $data
     * 
     * @return Model
     */
    abstract public function update(Model $model, array $data): Model;

    /**
     * Function remove model instance.
     * 
     * @param Model $model
     * 
     * @return bool
     */
    abstract public function delete(Model $model): bool;
}