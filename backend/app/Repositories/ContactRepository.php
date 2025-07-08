<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactRepository extends EloquentBaseRepository implements ContactRepositoryInterface
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
    }
}
