<?php

namespace App\Services;

use App\Interfaces\ServiceInterface;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService implements ServiceInterface
{
    protected BaseRepository $repository;

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