<?php

namespace App\Repositories\Message;

use App\Models\Message\MessageReaction;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\Message\MessageReactionRepositoryInterface;

class MessageReactionRepository extends EloquentBaseRepository implements MessageReactionRepositoryInterface
{
    public function __construct(MessageReaction $model)
    {
        parent::__construct($model);
    }
}
