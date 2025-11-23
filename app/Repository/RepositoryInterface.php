<?php

declare(strict_types=1);

namespace App\Repository;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * @template T of Data
 */
interface RepositoryInterface
{
    /**
     * Get an entity by its ID.
     *
     *
     * @return T
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function find(int $id): Data;

    /**
     * Get collection of all current entities.
     *
     * @return Collection<T>
     */
    public function all(): Collection;

    /**
     * Does the entity already exist in the database?
     */
    public function exists(int $id): bool;

    /**
     * Save an entity to the database. Returns a new entity representing all information saved to the DB.
     *
     * @param  T  $data
     * @return T
     */
    public function create(Data $data): Data;
}
