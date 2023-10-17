<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class TeamRepository extends BaseRepository
{
    public function __construct(Team $model)
    {
        $this->model = $model;
    }
}