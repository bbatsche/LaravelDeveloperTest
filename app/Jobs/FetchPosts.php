<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\PostData;
use App\Repository\PostRepository;
use App\Repository\ProfileRepository;
use App\Repository\RepositoryInterface;
use App\Services\EntityFetcherInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class FetchPosts implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     *
     * @param  PostRepository  $postRepo
     * @param  ProfileRepository  $profileRepo
     */
    public function handle(
        EntityFetcherInterface $client,
        RepositoryInterface $postRepo,
        RepositoryInterface $profileRepo,
    ): void {
        $client->fetchEntities()->each(function (PostData $post) use ($postRepo, $profileRepo): void {
            if (! $profileRepo->exists($post->profileId)) {
                // There is no profile for this post; has profile job not been run yet?
                return;
            }

            if (! $postRepo->exists($post->postId)) {
                $postRepo->create($post);
            }
        });
    }
}
