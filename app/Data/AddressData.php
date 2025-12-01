<?php

declare(strict_types=1);

namespace App\Data;

use DateTimeImmutable;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class AddressData extends Data
{
    #[Computed]
    public private(set) string $fullZip;

    public function __construct(
        #[GreaterThan(0)]
        public private(set) Optional|int $id,
        #[Min(2), Max(191)]
        public private(set) string $street,
        #[Max(191)]
        public private(set) Optional|null|string $suite,
        #[Max(191)]
        public private(set) string $city,
        #[Digits(5)]
        public private(set) string $zip,
        #[Digits(4)]
        public private(set) Optional|null|string $zip4,
        #[Min(-90), Max(90)]
        public private(set) float $latitude,
        #[Min(-180), Max(180)]
        public private(set) float $longitude,
        public private(set) Optional|DateTimeImmutable $createdAt,
        public private(set) Optional|DateTimeImmutable $updatedAt,
    ) {
        $this->fullZip = (is_null($this->zip4) || $this->zip4 instanceof Optional)
            ? $this->zip
            : $this->zip.'-'.$this->zip4;
    }

    public static function fromMultiple(
        int $id,
        string $street,
        Optional|null|string $suite,
        string $city,
        string $zip,
        Optional|null|string $zip4,
        float $latitude,
        float $longitude,
        Optional|DateTimeImmutable $createdAt,
        Optional|DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $id,
            $street,
            $suite,
            $city,
            $zip,
            $zip4,
            $latitude,
            $longitude,
            $createdAt,
            $updatedAt,
        );
    }
}
