<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Service for interacting with JSON Placeholder API.
 */
interface PlaceholderApiInterface
{
    /**
     * Get all users.
     */
    public function getUsers(): array;

    /**
     * Get all posts.
     */
    public function getPosts(): array;
}
