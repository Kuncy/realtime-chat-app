<?php

namespace App\Repositories;

use App\Models\Message\Message;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\Message\MessageRepositoryInterface;

class MessageRepository extends EloquentBaseRepository implements MessageRepositoryInterface
{
    public function __construct(Message $model)
    {
        parent::__construct($model);
    }
}
