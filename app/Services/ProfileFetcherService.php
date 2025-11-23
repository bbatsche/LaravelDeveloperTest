<?php

declare(strict_types=1);

namespace App\Services;

use App\Data\ProfileData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class ProfileFetcherService
{
    public function __construct(
        private PlaceholderApiInterface $client
    ) {}

    /**
     * Fetch all profiles from the JSONPlaceholder API.
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
     * Parse phone number into phone and extension parts.
     *
     * Handles various formats:
     * - "1-770-736-8031 x56442" -> ["7707368031", "56442"]
     * - "010-692-6593 x09125" -> ["0106926593", "09125"]
     * - "1-463-123-4447" -> ["4631234447", null]
     *
     * @return array{0: string, 1: null|string}
     */
    private function parsePhone(string $phone): array
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
    private function normalizeWebsite(string $website): string
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
    private function transformAddress(array $address): array
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
    private function parseZipCode(string $zipcode): array
    {
        return str_contains($zipcode, '-')
            ? explode('-', $zipcode)
            : [$zipcode, null];
    }
}
