<?php

declare(strict_types=1);

use App\Data\ProfileData;

describe('ProfileData generated values', function (): void {
    it('generated a tel: href and phone without extension', function (): void {
        $profile = ProfileData::from([
            'profile_id' => 1,
            'name' => 'Leanne Graham',
            'username' => 'Bret',
            'email' => 'leanne@example.com',
            'phone' => '7707368031',
            'website' => 'http://hildegard.org',
        ]);

        expect($profile->telHref)->toBe('tel:7707368031');
        expect($profile->formattedPhone)->toBe('(770) 736-8031');
    });

    it('generated a tel: href and phone with extension', function (): void {
        $profile = ProfileData::from([
            'profile_id' => 1,
            'name' => 'Leanne Graham',
            'username' => 'Bret',
            'email' => 'leanne@example.com',
            'phone' => '6749982850',
            'extension' => '4739',
            'website' => 'http://hildegard.org',
        ]);

        expect($profile->telHref)->toBe('tel:6749982850,4739');
        expect($profile->formattedPhone)->toBe('(674) 998-2850 ext. 4739');
    });
});
