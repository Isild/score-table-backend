<?php

namespace App\Services;

use App\Repositories\TeamRepository;

class TeamService extends BaseService
{
    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }
}