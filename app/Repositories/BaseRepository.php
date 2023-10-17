<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository extends AbstractRepository
{
    protected Model $model;

    /**
     * @inheritDoc
     */
    public function getAll(array $filters): LengthAwarePaginator
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): Model
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): Model
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function update(Model $model, array $data): Model
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function delete(Model $model): bool
    {
        //
    }
}