<?php

declare(strict_types=1);

use App\Data\AddressData;
use App\Data\CompanyData;
use App\Data\ProfileData;
use App\Services\PlaceholderApiInterface;
use App\Services\ProfileFetcherService;

describe('parseZip()', function (): void {
    $service = new ProfileFetcherService(Mockery::mock(PlaceholderApiInterface::class));

    it('splits zip and 4 digit extension', function () use ($service): void {
        expect($service->parseZipCode('12345-6789'))->toBe(['12345', '6789']);
    });

    it('returns null for the second index with no +4 extension', function () use ($service): void {
        expect($service->parseZipCode('98765'))->toBe(['98765', null]);
    });
});

describe('transformAddress()', function (): void {
    it('flattens address data', function (): void {
        $service = new ProfileFetcherService(Mockery::mock(PlaceholderApiInterface::class));
        $addressData = [
            'street' => 'Victor Plains',
            'suite' => 'Suite 879',
            'city' => 'Wisokyburgh',
            'zipcode' => '90566-7771',
            'geo' => [
                'lat' => '-43.9509',
                'lng' => '-34.4618',
            ],
        ];

        $result = $service->transformAddress($addressData);

        expect($result)->toHaveKeys(['zip', 'zip4', 'latitude', 'longitude']);
        expect($result['zip'])->toBe('90566');
        expect($result['zip4'])->toBe('7771');
        expect($result['latitude'])->toBe(-43.9509);
        expect($result['longitude'])->toBe(-34.4618);
        expect($result)->not()->toHaveKeys(['zipcode', 'geo']);
    });
});

describe('normalizeWebsite()', function (): void {
    $service = new ProfileFetcherService(Mockery::mock(PlaceholderApiInterface::class));

    it('does not modify websites that include a scheme', function () use ($service): void {
        expect($service->normalizeWebsite('https://example.com'))->toBe('https://example.com');
        expect($service->normalizeWebsite('http://foo.example.com'))->toBe('http://foo.example.com');
    });

    it('prepends https to URLs missing it', function () use ($service): void {
        expect($service->normalizeWebsite('another.example.com'))->toBe('https://another.example.com');
    });
});

describe('parsePhone()', function (): void {
    $service = new ProfileFetcherService(Mockery::mock(PlaceholderApiInterface::class));

    it('removes non-digits from the phone number', function () use ($service): void {
        expect($service->parsePhone('(210) 555-4820'))->toBe(['2105554820', null]);
    });

    it('trims the the North American country code from the front', function () use ($service): void {
        expect($service->parsePhone('1-512-555-4785'))->toBe(['5125554785', null]);
    });

    it('splits the extension to the second index', function () use ($service): void {
        expect($service->parsePhone('830.555.2984 x5721'))->toBe(['8305552984', '5721']);
    });
});

describe('transformResponseData()', function (): void {
    it('prepares response data for being wrapped by ProfileData', function (): void {
        $service = new ProfileFetcherService(Mockery::mock(PlaceholderApiInterface::class));
        $responseData = [
            'id' => 2,
            'name' => 'Ervin Howell',
            'username' => 'Antonette',
            'email' => 'Shanna@melissa.tv',
            'phone' => '1 (010) 692-6593 x09125',
            'website' => 'anastasia.net',
            'address' => [
                'street' => 'Victor Plains',
                'suite' => 'Suite 879',
                'city' => 'Wisokyburgh',
                'zipcode' => '90566-7771',
                'geo' => [
                    'lat' => '-43.9509',
                    'lng' => '-34.4618',
                ],
            ],
            'company' => [
                'name' => 'Deckow-Crist',
                'catchPhrase' => 'Proactive didactic contingency',
                'bs' => 'synergize scalable supply-chains',
            ],
        ];

        $result = $service->transformResponseData($responseData);

        expect($result)->toHaveKeys(['profile_id', 'extension']);
        expect($result)->not()->toHaveKey('id');
        expect($result['phone'])->toBe('0106926593');
        expect($result['extension'])->toBe('09125');
        expect($result['website'])->toBe('https://anastasia.net');
        expect($result['address'])->toHaveKeys(['zip', 'zip4', 'latitude', 'longitude']);
        expect($result['address'])->not()->toHaveKeys(['zipcode', 'geo']);
        expect($result['address']['zip'])->toBe('90566');
        expect($result['address']['zip4'])->toBe('7771');
        expect($result['address']['latitude'])->toBe(-43.9509);
        expect($result['address']['longitude'])->toBe(-34.4618);
        expect($result['company'])->toHaveKeys(['boilerplate', 'catch_phrase']);
        expect($result['company'])->not()->toHaveKeys(['bs', 'catchPhrase']);
        expect($result['company']['boilerplate'])->toBe('synergize scalable supply-chains');
        expect($result['company']['catch_phrase'])->toBe('Proactive didactic contingency');
    });
});

describe('fetchProfiles()', function (): void {
    it('can handle an empty response', function (): void {
        $mockClient = Mockery::mock(PlaceholderApiInterface::class);
        $service = new ProfileFetcherService($mockClient);

        $mockClient->expects()
            ->getUsers()
            ->andReturn([]);

        expect($service->fetchProfiles())->toBeEmpty();
    });

    it('wraps response in a collection of ProfileData', function (): void {
        $mockClient = Mockery::mock(PlaceholderApiInterface::class);
        $service = new ProfileFetcherService($mockClient);
        $response = [
            [
                'id' => 1,
                'name' => 'Leanne Graham',
                'username' => 'Bret',
                'email' => 'leanne@example.com',
                'phone' => '770-736-8031',
                'website' => 'http://hildegard.org',
                'address' => [
                    'street' => 'Kulas Light',
                    'suite' => 'Apt. 556',
                    'city' => 'Gwenborough',
                    'zipcode' => '92998',
                    'geo' => [
                        'lat' => '-37.3159',
                        'lng' => '81.1496',
                    ],
                ],
                'company' => [
                    'name' => 'Romaguera-Crona',
                    'catchPhrase' => 'Multi-layered client-server neural-net',
                    'bs' => 'harness real-time e-markets',
                ],
            ], [
                'id' => 2,
                'name' => 'Ervin Howell',
                'username' => 'Antonette',
                'email' => 'Shanna@melissa.tv',
                'phone' => '1 (010) 692-6593 x09125',
                'website' => 'anastasia.net',
                'address' => [
                    'street' => 'Victor Plains',
                    'suite' => 'Suite 879',
                    'city' => 'Wisokyburgh',
                    'zipcode' => '90566-7771',
                    'geo' => [
                        'lat' => '-43.9509',
                        'lng' => '-34.4618',
                    ],
                ],
                'company' => [
                    'name' => 'Deckow-Crist',
                    'catchPhrase' => 'Proactive didactic contingency',
                    'bs' => 'synergize scalable supply-chains',
                ],
            ],
        ];

        $mockClient->expects()
            ->getUsers()
            ->andReturn($response);

        $profiles = $service->fetchProfiles();

        expect($profiles)->toHaveCount(2);
        expect($profiles->first())->toBeInstanceOf(ProfileData::class);
        expect($profiles->first()->profileId)->toBe(1);
        expect($profiles->first()->name)->toBe('Leanne Graham');
        expect($profiles->first()->address)->toBeInstanceOf(AddressData::class);
        expect($profiles->first()->company)->toBeInstanceOf(CompanyData::class);
        expect($profiles->get(1)->profileId)->toBe(2);
        expect($profiles->get(1)->name)->toBe('Ervin Howell');
    });
});
