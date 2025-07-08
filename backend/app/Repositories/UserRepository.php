<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
