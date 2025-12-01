<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

/**
 * Services that fetch a given entity type from the Placeholder API.
 *
 * @template T of Data
 */
interface EntityFetcherInterface
{
    /**
     * Get entities from JSON Placeholder API.
     *
     * @return Collection<T>
     */
    public function fetchEntities(): Collection;
}
