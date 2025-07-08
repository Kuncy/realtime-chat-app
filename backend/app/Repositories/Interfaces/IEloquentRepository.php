<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IEloquentRepository
{
    public function getById(int|string $id): Model;

    public function create(array $data): Model;

    public function afterCreate(Model $entity, array $data);

    public function beforeCreate(array &$data): void;

    public function deleteById(int $id): bool;

    public function update(int|string $id, array $data): Model;

    public function afterUpdate(Model $entity, array $data): Model;

    public function beforeUpdate(array &$data): void;

    public function getFilteredCollection(
        int    $perPage = 20,
        int    $page = 1,
        string $orderBy = 'id',
        bool   $orderDesc = false,
               $filters = []
    );

    /**
     * @return Model[]|Collection<Model>
     * @author Robin A. Rick - Yotta Byte Manager GmbH
     */
    public function getAll(): array|Collection;
}
