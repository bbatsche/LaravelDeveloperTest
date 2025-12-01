<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\ProfileData;
use App\Repository\ProfileRepository;
use App\Repository\RepositoryInterface;
use App\Services\EntityFetcherInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

final class FetchProfiles implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     *
     * @param  ProfileRepository  $repo
     */
    public function handle(EntityFetcherInterface $client, RepositoryInterface $repo): void
    {
        Log::info('Starting Fetch Profiles Job');

        $client->fetchEntities()->each(function (ProfileData $profile) use ($repo): void {
            if ($repo->exists($profile->profileId)) {
                Log::debug('Profile already exists in database', compact('profile'));

                return;
            }

            $repo->create($profile);
            Log::info('Profile created', compact('profile'));
        });

        Log::info('Fetch Profiles Job Completed');
    }
}
