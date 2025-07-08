<?php

namespace App\Repositories;

use App\Models\Group;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\GroupRepositoryInterface;

class GroupRepository extends EloquentBaseRepository implements GroupRepositoryInterface
{
    public function __construct(Group $model)
    {
        parent::__construct($model);
    }
}
