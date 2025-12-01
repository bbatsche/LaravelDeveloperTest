<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\ProfileData;
use App\Repository\ProfileRepository;
use App\Repository\RepositoryInterface;
use App\Services\EntityFetcherInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
        $client->fetchEntities()->each(function (ProfileData $profile) use ($repo): void {
            if (! $repo->exists($profile->profileId)) {
                $repo->create($profile);
            }
        });
    }
}
