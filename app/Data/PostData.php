<?php

declare(strict_types=1);

namespace App\Data;

use DateTimeImmutable;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class PostData extends Data
{
    public function __construct(
        #[GreaterThan(0)]
        public private(set) Optional|int $id,
        #[GreaterThan(0)]
        public private(set) int $postId,
        #[GreaterThan(0)]
        public private(set) int $profileId,
        #[Min(2), Max(191)]
        public private(set) string $title,
        public private(set) string $body,
        public private(set) Optional|ProfileData $profile,
        public private(set) Optional|DateTimeImmutable $createdAt,
        public private(set) Optional|DateTimeImmutable $updatedAt,
    ) {}
}
