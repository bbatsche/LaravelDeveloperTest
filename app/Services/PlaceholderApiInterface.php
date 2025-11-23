<?php

declare(strict_types=1);

namespace App\Services;

interface PlaceholderApiInterface
{
    public function getUsers(): array;

    public function getPosts(): array;
}
