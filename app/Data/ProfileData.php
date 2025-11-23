<?php

declare(strict_types=1);

namespace App\Data;

use DateTimeInterface;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\GreaterThan;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Regex;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class ProfileData extends Data
{
    #[Computed]
    public private(set) string $telHref;

    #[Computed]
    public private(set) string $formattedPhone;

    public function __construct(
        #[GreaterThan(0)]
        public private(set) Optional|int $id,
        #[GreaterThan(0)]
        public private(set) int $profileId,
        #[Min(2), Max(191)]
        public private(set) string $name,
        #[Min(2), Max(191), Regex('/^[a-zA-Z0-9-_.]+$/')]
        public private(set) string $username,
        #[Max(191), Email]
        public private(set) string $email,
        #[Digits(10)]
        public private(set) string $phone,
        #[DigitsBetween(1, 8)]
        public private(set) Optional|null|string $extension,
        #[Url]
        public private(set) string $website,
        public private(set) Optional|AddressData $address,
        public private(set) Optional|CompanyData $company,
        public private(set) Optional|DateTimeInterface $createdAt,
        public private(set) Optional|DateTimeInterface $updatedAt,
    ) {
        $this->telHref = is_null($this->extension)
            ? "tel:{$this->phone}"
            : "tel:{$this->phone},{$this->extension}";
        $this->formattedPhone = '('.substr($this->phone, 0, 3).') '.substr($this->phone, 3, 3).'-'.substr($this->phone, 6);

        if (! is_null($this->extension)) {
            $this->formattedPhone .= " ext. {$this->extension}";
        }
    }

    public static function fromMultiple(
        Optional|int $id,
        int $profileId,
        string $name,
        string $username,
        string $email,
        string $phone,
        Optional|null|string $extension,
        string $website,
        Optional|AddressData $address,
        Optional|CompanyData $company,
        Optional|DateTimeInterface $createdAt,
        Optional|DateTimeInterface $updatedAt,
    ): self {
        return new self(
            $id,
            $profileId,
            $name,
            $username,
            $email,
            $phone,
            $extension,
            $website,
            $address,
            $company,
            $createdAt,
            $updatedAt,
        );
    }
}
