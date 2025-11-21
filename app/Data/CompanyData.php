<?php
declare(strict_types=1);

namespace App\Data;

use DateTimeInterface;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class CompanyData extends Data
{
    private function __construct(
        #[GreaterThan(0)]
        public private(set) Optional|int $id,
        #[GreaterThan(0)]
        public private(set) Optional|int $profileId,
        #[Min(2), Max(191)]
        public private(set) string $name,
        public private(set) string $catchPhrase,
        #[Max(191)]
        public private(set) string $boilerplate,
        public private(set) Optional|DateTimeInterface $createdAt,
        public private(set) Optional|DateTimeInterface $updatedAt,
    ) {}

    public static function fromMultiple(
        Optional|int $id,
        Optional|int $profileId,
        string $name,
        string $catchPhrase,
        string $boilerplate,
        Optional|DateTimeInterface $createdAt,
        Optional|DateTimeInterface $updatedAt,
    ): self {
        return new self($id, $profileId, $name, $catchPhrase, $boilerplate, $createdAt, $updatedAt);
    }
}
