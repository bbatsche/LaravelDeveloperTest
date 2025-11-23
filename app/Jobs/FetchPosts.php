<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Data\PostData;
use App\Repository\PostRepository;
use App\Repository\ProfileRepository;
use App\Services\PostFetcherService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class FetchPosts implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        PostFetcherService $client,
        PostRepository $postRepo,
        ProfileRepository $profileRepo,
    ): void {
        $client->fetchPosts()->each(function (PostData $post) use ($postRepo, $profileRepo): void {
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
