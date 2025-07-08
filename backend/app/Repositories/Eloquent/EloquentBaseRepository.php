<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\IEloquentRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EloquentBaseRepository implements IEloquentRepository
{
    public Model $model;

    /**
     * @var string[] $ignoreKeys
     */
    protected array $ignoreKeys = [];

    protected array $preloadRelations = [];

    /**
     * @var array|string[]
     *
     * @description We always want to ignore these fields on update, as they are set automatically by laravel
     */
    protected array $ignoreKeysAutoFields = ['id', 'updated_at', 'created_at', 'deleted_at'];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param int|string $id
     * @return Model
     * @author Robin A. Rick - Yotta Byte Manager GmbH
     */
    public function getById(int|string $id): Model
    {
        if(count($this->preloadRelations)>0){
            return $this->model->with($this->preloadRelations)->findOrFail($id);
        }
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $data
     * @return Model
     * @throws Throwable
     * @author Robin A. Rick - Yotta Byte Manager GmbH
     */
    public function create(array $data): Model
    {
        try {
            DB::beginTransaction();
            $this->beforeCreate($data);
            $newEntity = new $this->model();

            foreach ($data as $key => $value) {
                if (
                    Schema::hasColumn($newEntity->getTable(), $key)
                    && !in_array($key, $this->ignoreKeys, true)
                    && !in_array($key, $this->ignoreKeysAutoFields, true)) {
                    $newEntity->$key = $value;
                }
            }

            $newEntity->save();
            $newEntity = $this->afterCreate($newEntity, $data);

            if(count($this->preloadRelations) >0){
                $newEntity->with($this->preloadRelations);
            }

            DB::commit();
            return $newEntity;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Model $entity
     * @param array $data
     * @return Model
     * @author Robin A. Rick - Yotta Byte Manager GmbH
     */
    public function afterCreate(Model $entity, array $data): Model
    {
        return $entity;
    }

    public function deleteById(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * @throws Throwable
     */
    public function update(int|string $id, array $data): Model
    {
        try {
            DB::beginTransaction();
            $this->beforeUpdate($data);
            $entity = $this->model->findOrFail($id);

            foreach ($data as $key => $value) {
                if (
                    Schema::hasColumn($entity->getTable(), $key)
                    && !in_array($key, $this->ignoreKeys, true)
                    && !in_array($key, $this->ignoreKeysAutoFields, true)
                ) {
                    $entity->$key = $value;
                }
            }

            $entity->save();

            $entity = $this->afterUpdate($entity, $data);

            if(count($this->preloadRelations) >0){
                $entity->with($this->preloadRelations);
            }
            DB::commit();

            return $entity;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getFilteredCollection(
        int    $perPage = 20,
        int    $page = 1,
        string $orderBy = 'id',
        bool   $orderDesc = false,
               $filters = []): LengthAwarePaginator
    {
        $query = $this->model::query();
        $query = $this->hookAdditionalFilterGetCollection($query, $filters);
        $query = $this->filterCollection($query, $filters);

        if(count($this->preloadRelations) >0){
            $query->with($this->preloadRelations);
        }

        return $query->paginate(perPage: $perPage, page: $page);
    }

    protected function filterCollection(Builder $query, array &$filters): Builder
    {
        if (count($filters) > 0) {
            foreach ($filters as $filter) {
                if (str_contains($filter['key'], '.')) {
                    try {
                        $matches = [];
                        $regex = "/(.*(\.))(.*)/";
                        $regexKeyO = preg_match($regex, $filter['key'], $matches);
                        $relationAttribute = $matches[3];
                        $relationString = substr($matches[1], 0, -1);

                        $query->whereHas($relationString, function (Builder $query) use ($relationAttribute, $filter, $relationString) {
                            $value = $filter['value'];
                            if (isset($filter['type']) && $filter['type'] !== '') {
                                if ($filter['type'] === 'date') {
                                    if (is_bool($value)) {
                                        return $query->where($relationAttribute, '=', $value);
                                    }
                                    return $query->whereDate($relationAttribute, '=', "%$value%");
                                }
                            } else {
                                if (is_bool($value) || $relationAttribute === 'id' || str_contains($relationAttribute, 'id')) {
                                    return $query->where($relationAttribute, '=', $value);
                                }
                                return $query->where($relationAttribute, 'ilike', "%$value%");
                            }
                            return $query;
                        });
                    } catch (Throwable $e) {
                        Log::error($e);
                        // todo: fehlerbehandlung
                    }
                    continue;
                }
                if ($filter['type'] === 'dateRange') {
                    $query->where($filter['key'], '>=', Carbon::parse($filter['value']['from'])->format('Y-m-d'))
                        ->where($filter['key'], '<=', Carbon::parse($filter['value']['to'])->format('Y-m-d'));
                } else {
                    if(str_contains($filter['key'],'id')){
                        $query->where($filter['key'], '=', $filter['value']);
                    } else {
                        $query->where($filter['key'], 'ilike', "%{$filter['value']}%");
                    }
                }
            }
        }
        return $query;
    }

    protected function hookAdditionalFilterGetCollection(Builder $query, array &$filters): Builder
    {
        return $query;
    }

    public function afterUpdate(Model $entity, array $data): Model
    {
        return $entity;
    }

    public function beforeCreate(array &$data): void
    {
    }

    public function beforeUpdate(array &$data): void
    {
    }

    public function getAll(): array|Collection
    {

        if(count($this->preloadRelations) >0){
            return $this->model::query()->with($this->preloadRelations)->get();
        }
        return $this->model::all();
    }
}
