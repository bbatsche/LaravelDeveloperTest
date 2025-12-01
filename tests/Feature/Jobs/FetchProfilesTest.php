<?php

declare(strict_types=1);

use App\Data\ProfileData;
use App\Jobs\FetchProfiles;
use App\Repository\RepositoryInterface;
use App\Services\EntityFetcherInterface;
use Illuminate\Support\Collection;

describe('Profile Fetcher Job', function (): void {
    $job = new FetchProfiles;

    it('skips profiles that already exist', function () use ($job): void {
        $client = Mockery::mock(EntityFetcherInterface::class);
        $repo = Mockery::mock(RepositoryInterface::class);
        $profiles = ProfileData::collect([[
            'profile_id' => 1,
            'name' => 'Mister Rogers',
            'username' => 'neighborhood',
            'email' => 'hello@example.com',
            'phone' => '2105554839',
            'website' => 'https://example.com',
        ]], Collection::class);

        $client->expects()
            ->fetchEntities()
            ->andReturn($profiles);
        $repo->allows()
            ->exists(1)
            ->andReturn(true);
        $repo->shouldNotReceive('create');

        $job->handle($client, $repo);
    });

    it('creates profiles', function () use ($job): void {
        $client = Mockery::mock(EntityFetcherInterface::class);
        $repo = Mockery::mock(RepositoryInterface::class);
        $profiles = ProfileData::collect([[
            'profile_id' => 1,
            'name' => 'Mister Rogers',
            'username' => 'neighborhood',
            'email' => 'hello@example.com',
            'phone' => '2105554839',
            'website' => 'https://example.com',
        ]], Collection::class);

        $client->expects()
            ->fetchEntities()
            ->andReturn($profiles);
        $repo->allows()
            ->exists(1)
            ->andReturn(false);
        $repo->expects()
            ->create($profiles->first());

        $job->handle($client, $repo);
    });
});
