<?php

namespace App\Services;

use App\Models\Team;
use App\Repositories\BaseRepository;


class TeamService extends BaseService
{
    public function __construct(protected BaseRepository $repository)
    {
        //
    }
}