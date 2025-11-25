<?php

declare(strict_types=1);

use App\Data\ProfileData;

describe('ProfileData generated values', function (): void {
    $profile = ProfileData::from([
        'profile_id' => 1,
        'name' => 'Leanne Graham',
        'username' => 'Bret',
        'email' => 'leanne@example.com',
        'phone' => '7707368031',
        'website' => 'http://hildegard.org',
    ]);

    it('generated a tel: href', function () use ($profile): void {
        expect($profile->telHref)->toBe('tel:7707368031');
    });
});
