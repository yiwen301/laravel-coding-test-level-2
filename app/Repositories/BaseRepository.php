<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Container\Container;
use Illuminate\Support\Collection;

/**
 * @author    Yi Wen, Tan <yiwentan301@gmail.com>
 */
abstract class BaseRepository {
    /** @var Builder|Model */
    protected $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct() {
        try {
            $this->makeModel();
        } catch (BindingResolutionException $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
    }

    abstract public function model(): string;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function makeModel(): Model {
        /** @var Model $model */
        $model = Container::getInstance()->make($this->model());

        return $this->model = $model;
    }

    /**
     * @param string     $field
     * @param            $value
     * @param array|null $columns
     *
     * @return Model
     */
    public function findBy(string $field, $value, array $columns = null): Model {
        $record = $this->model->where($field, '=', $value)->select($columns ?? ['*'])->first();

        if ($record === null) {
            throw new ModelNotFoundException("Unable to find record where {$field} = {$value} in model {$this->model()}");
        }

        return $record;
    }

    /**
     * @param array      $values
     * @param array|null $columns
     *
     * @return Model
     */
    public function findWhere(array $values, array $columns = null): Model {
        $instance = $this->findAllWhere($values, $columns ?? ['*'])->first();
        if ($instance === null) {
            throw new ModelNotFoundException('Unable to find any results for the given where clauses');
        }

        return $instance;
    }

    /**
     * @param array          $values
     * @param array|string[] $columns
     *
     * @return Collection
     */
    public function findAllWhere(array $values, array $columns = ['*']): Collection {
        $query = $this->model->newQuery();

        foreach ($values as $field => $value) {
            $query->where($field, $value);
        }

        return $query->get($columns);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data): Model {
        $model = $this->model;

        $instance = new $model();

        $instance->fill($data)->save();

        return $instance;
    }

    /**
     * @param string $field
     * @param        $value
     * @param array  $data
     *
     * @return bool
     */
    public function update(string $field, $value, array $data = []): bool {
        return $this->findBy($field, $value)->update($data);
    }

    /**
     * @param array $conditionals
     * @param array $data
     *
     * @return bool
     */
    public function updateWhere(array $conditionals, array $data): bool {
        $instance = $this->findWhere($conditionals);

        return $instance->update($data);
    }

    /**
     * @param string $field
     * @param string $value
     */
    public function delete(string $field, string $value): void {
        $this->findBy($field, $value)->delete();
    }
}
