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
use Illuminate\Support\Facades\Log;

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
        Log::info('Starting Fetch Posts Job');

        $client->fetchEntities()->each(function (PostData $post) use ($postRepo, $profileRepo): void {
            if (! $profileRepo->exists($post->profileId)) {
                Log::warning('No profile found for post!', compact('post'));

                return;
            }

            if ($postRepo->exists($post->postId)) {
                Log::debug('Post already exists in database', compact('post'));

                return;
            }

            $postRepo->create($post);
            Log::info('Post created', compact('post'));
        });

        Log::info('Fetch Posts Job Completed');
    }
}
