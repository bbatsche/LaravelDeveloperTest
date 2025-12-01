<?php

declare(strict_types=1);

use App\Data\AddressData;

describe('AddressData generated values', function (): void {
    it('generated a full zip without the +4 extension', function (): void {
        $address = AddressData::from([
            'street' => 'Victor Plains',
            'city' => 'Wisokyburgh',
            'zip' => '90566',
            'latitude' => '-43.9509',
            'longitude' => '-34.4618',
        ]);

        expect($address->fullZip)->toBe('90566');
    });

    it('generated a full zip with the +4 extension', function (): void {
        $address = AddressData::from([
            'street' => 'Victor Plains',
            'city' => 'Wisokyburgh',
            'zip' => '90566',
            'zip4' => '3822',
            'latitude' => '-43.9509',
            'longitude' => '-34.4618',
        ]);

        expect($address->fullZip)->toBe('90566-3822');
    });
});
