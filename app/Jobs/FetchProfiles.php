<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\ProfileData;
use App\Repository\ProfileRepository;
use App\Services\ProfileFetcherService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class FetchProfiles implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(ProfileFetcherService $client, ProfileRepository $repo): void
    {
        $client->fetchProfiles()->each(function (ProfileData $profile) use ($repo): void {
            if (! $repo->exists($profile->profileId)) {
                $repo->create($profile);
            }
        });
    }
}
