<?php

namespace App\Repositories;

use App\Models\File;
use App\Repositories\Eloquent\EloquentBaseRepository;
use App\Repositories\Interfaces\FileRepositoryInterface;

class FileRepository extends EloquentBaseRepository implements FileRepositoryInterface
{
    public function __construct(File $model)
    {
        parent::__construct($model);
    }
}
