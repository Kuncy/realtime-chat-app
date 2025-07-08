<?php

namespace App\Repositories;

use App\Models\Message\MessagePin;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\Message\MessagePinRepositoryInterface;

class MessagePinRepository extends EloquentBaseRepository implements MessagePinRepositoryInterface
{
    public function __construct(MessagePin $model)
    {
        parent::__construct($model);
    }
}
