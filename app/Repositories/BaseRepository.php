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
        return $this->model::class::paginate($filters['limit'] ?? 50);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): Model
    {
        return $this->model::class::where('id', '=', $id)->first();
    }

    /**
     * @inheritDoc
     */
    public function create(array $data): Model
    {
        return $this->model::class::create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(Model $model, array $data): Model
    {
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }
        $model->saveOrFail();

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}