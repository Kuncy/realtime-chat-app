<?php

namespace App\Repositories;

use App\Models\Message\MessageReaction;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\Message\MessageStatusRepositoryInterface;

class MessageStatusRepository extends EloquentBaseRepository implements MessageStatusRepositoryInterface
{
    public function __construct(MessageReaction $model)
    {
        parent::__construct($model);
    }

}
