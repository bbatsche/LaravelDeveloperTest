<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\ProfileData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Utility for fetching user/profile data from JSON Placeholder API and transforming it into data objects.
 */
final class ProfileFetcherService
{
    public function __construct(
        private PlaceholderApiInterface $client
    ) {}

    /**
     * Get all users/profiles and wrap them in a Collection of ProfileData
     *
     * @return Collection<ProfileData>
     */
    public function fetchProfiles(): Collection
    {
        $users = $this->client->getUsers();

        return ProfileData::collect(
            Arr::map(
                $users,
                fn (array $user) => $this->transformResponseData($user)
            ),
            Collection::class,
        );
    }

    /**
     * Modify & cleanup data from JSON Placeholder API to be inline with what ProfileData expects.
     */
    public function transformResponseData(array $user): array
    {
        $user['profile_id'] = $user['id'];
        unset($user['id']);

        [$user['phone'], $user['extension']] = $this->parsePhone($user['phone']);
        $user['website'] = $this->normalizeWebsite($user['website']);
        $user['address'] = $this->transformAddress($user['address']);

        $user['company']['boilerplate'] = $user['company']['bs'];
        $user['company']['catch_phrase'] = $user['company']['catchPhrase'];
        unset($user['company']['bs'], $user['company']['catchPhrase']);

        return $user;
    }

    /**
     * Parse phone number into phone and extension parts with only digits.
     *
     * @return array{0: string, 1: null|string}
     */
    public function parsePhone(string $phone): array
    {
        $extension = null;

        if (str_contains($phone, 'x')) {
            [$phone, $extension] = explode('x', $phone);
        }

        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) === 11 && str_starts_with($phone, '1')) {
            $phone = substr($phone, 1);
        }

        return [$phone, $extension];
    }

    /**
     * Normalize website URL to ensure it has a scheme.
     */
    public function normalizeWebsite(string $website): string
    {
        // If no scheme is present, add https://
        if (! str_starts_with($website, 'http://') && ! str_starts_with($website, 'https://')) {
            return "https://{$website}";
        }

        return $website;
    }

    /**
     * Transform address array into structure compatible with AddressData.
     */
    public function transformAddress(array $address): array
    {
        [$address['zip'], $address['zip4']] = $this->parseZipCode($address['zipcode']);
        $address['latitude'] = (float) $address['geo']['lat'];
        $address['longitude'] = (float) $address['geo']['lng'];

        unset($address['zipcode'], $address['geo']);

        return $address;
    }

    /**
     * Parse zipcode into zip and zip4 parts.
     *
     * Handles formats:
     * - "92998-3874" -> ["92998", "3874"]
     * - "59590" -> ["59590", null]
     *
     * @return array{0: string, 1: null|string}
     */
    public function parseZipCode(string $zipcode): array
    {
        return str_contains($zipcode, '-')
            ? explode('-', $zipcode)
            : [$zipcode, null];
    }
}
