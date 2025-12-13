<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function all(): Collection;

    /**
     * Find a record by ID
     */
    public function find(int $id): ?Model;

    /**
     * Find a record by ID or throw exception
     */
    public function findOrFail(int $id): Model;

    /**
     * Create a new record
     */
    public function create(array $data): Model;

    /**
     * Update a record
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record
     */
    public function delete(int $id): bool;

    /**
     * Get paginated results
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get records with relationships
     */
    public function with(array $relations): self;

    /**
     * Apply where conditions
     */
    public function where(string $column, $operator, $value = null): self;

    /**
     * Apply order by
     */
    public function orderBy(string $column, string $direction = 'asc'): self;

    /**
     * Get first record matching conditions
     */
    public function first(): ?Model;

    /**
     * Get count of records
     */
    public function count(): int;
}
