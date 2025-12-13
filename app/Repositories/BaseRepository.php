<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;
    protected $query;

    public function __construct()
    {
        $this->model = $this->getModel();
        $this->query = $this->model->newQuery();
    }

    /**
     * Get the model instance
     */
    abstract protected function getModel(): Model;

    /**
     * Reset the query builder
     */
    protected function resetQuery(): self
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    public function all(): Collection
    {
        return $this->resetQuery()->query->get();
    }

    public function find(int $id): ?Model
    {
        return $this->resetQuery()->query->find($id);
    }

    public function findOrFail(int $id): Model
    {
        return $this->resetQuery()->query->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->find($id);
        if (!$model) {
            return false;
        }
        return $model->update($data);
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query->paginate($perPage);
    }

    public function with(array $relations): self
    {
        $this->query->with($relations);
        return $this;
    }

    public function where(string $column, $operator, $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        $this->query->where($column, $operator, $value);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->query->orderBy($column, $direction);
        return $this;
    }

    public function first(): ?Model
    {
        return $this->query->first();
    }

    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * Apply whereHas condition
     */
    public function whereHas(string $relation, callable $callback = null): self
    {
        $this->query->whereHas($relation, $callback);
        return $this;
    }

    /**
     * Apply whereBetween condition
     */
    public function whereBetween(string $column, array $values): self
    {
        $this->query->whereBetween($column, $values);
        return $this;
    }

    /**
     * Apply take limit
     */
    public function take(int $limit): self
    {
        $this->query->take($limit);
        return $this;
    }

    /**
     * Apply withCount
     */
    public function withCount($relations): self
    {
        $this->query->withCount($relations);
        return $this;
    }

    /**
     * Apply withAvg
     */
    public function withAvg($relation, string $column): self
    {
        $this->query->withAvg($relation, $column);
        return $this;
    }

    /**
     * Apply withSum
     */
    public function withSum($relation, string $column): self
    {
        $this->query->withSum($relation, $column);
        return $this;
    }

    /**
     * Apply orderByDesc
     */
    public function orderByDesc(string $column): self
    {
        $this->query->orderByDesc($column);
        return $this;
    }

    /**
     * Get average value
     */
    public function avg(string $column)
    {
        return $this->query->avg($column);
    }

    /**
     * Get sum value
     */
    public function sum(string $column)
    {
        return $this->query->sum($column);
    }

    /**
     * Apply orWhere condition
     */
    public function orWhere(string $column, $operator, $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        $this->query->orWhere($column, $operator, $value);
        return $this;
    }

    /**
     * Apply whereDoesntHave condition
     */
    public function whereDoesntHave(string $relation, callable $callback = null): self
    {
        $this->query->whereDoesntHave($relation, $callback);
        return $this;
    }

    /**
     * Get the query builder for custom queries
     */
    public function getQuery(): Builder
    {
        return $this->query;
    }

    /**
     * Execute the query and get results
     */
    public function get(): Collection
    {
        return $this->query->get();
    }
}
