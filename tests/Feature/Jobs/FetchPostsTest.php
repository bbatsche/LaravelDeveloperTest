<?php

declare(strict_types=1);

use App\Data\PostData;
use App\Jobs\FetchPosts;
use App\Repository\RepositoryInterface;
use App\Services\EntityFetcherInterface;
use Illuminate\Support\Collection;

describe('Post Fetcher Job', function (): void {
    $job = new FetchPosts;

    it('skips posts that already exist', function () use ($job): void {
        $client = Mockery::mock(EntityFetcherInterface::class);
        $postRepo = Mockery::mock(RepositoryInterface::class);
        $profileRepo = Mockery::mock(RepositoryInterface::class);
        $posts = PostData::collect([[
            'post_id' => 1,
            'profile_id' => 2,
            'title' => 'Some Post',
            'body' => 'This is an amazing blog post',
        ]], Collection::class);

        $client->expects()
            ->fetchEntities()
            ->andReturn($posts);
        $profileRepo->allows()
            ->exists(2)
            ->andReturn(true);
        $postRepo->allows()
            ->exists(1)
            ->andReturn(true);
        $postRepo->shouldNotReceive('create');

        $job->handle($client, $postRepo, $profileRepo);
    });

    it('skips posts that do not have a profile', function () use ($job): void {
        $client = Mockery::mock(EntityFetcherInterface::class);
        $postRepo = Mockery::mock(RepositoryInterface::class);
        $profileRepo = Mockery::mock(RepositoryInterface::class);
        $posts = PostData::collect([[
            'post_id' => 1,
            'profile_id' => 2,
            'title' => 'Some Post',
            'body' => 'This is an amazing blog post',
        ]], Collection::class);

        $client->expects()
            ->fetchEntities()
            ->andReturn($posts);
        $profileRepo->allows()
            ->exists(2)
            ->andReturn(false);
        $postRepo->allows()
            ->exists(1)
            ->andReturn(false);
        $postRepo->shouldNotReceive('create');

        $job->handle($client, $postRepo, $profileRepo);
    });

    it('creates posts', function () use ($job): void {
        $client = Mockery::mock(EntityFetcherInterface::class);
        $postRepo = Mockery::mock(RepositoryInterface::class);
        $profileRepo = Mockery::mock(RepositoryInterface::class);
        $posts = PostData::collect([[
            'post_id' => 1,
            'profile_id' => 2,
            'title' => 'Some Post',
            'body' => 'This is an amazing blog post',
        ]], Collection::class);

        $client->expects()
            ->fetchEntities()
            ->andReturn($posts);
        $profileRepo->allows()
            ->exists(2)
            ->andReturn(true);
        $postRepo->allows()
            ->exists(1)
            ->andReturn(false);
        $postRepo->shouldNotReceive('create');

        $postRepo->expects()
            ->create($posts->first());

        $job->handle($client, $postRepo, $profileRepo);
    });
});
