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
        return $this->repository->getAll($filters);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): Model
    {
        return $this->repository->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(Model $model, array $data): Model
    {
        return $this->repository->update($model, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete(Model $model): bool
    {
        return $this->repository->delete($model);
    }
}