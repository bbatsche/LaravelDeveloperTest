<?php

declare(strict_types=1);

namespace App\Providers;

use App\Data\PostData;
use App\Data\ProfileData;
use App\Jobs\FetchPosts;
use App\Jobs\FetchProfiles;
use App\Repository\PostRepository;
use App\Repository\ProfileRepository;
use App\Services\PlaceholderApiInterface;
use App\Services\PlaceholderApiService;
use App\Services\PostFetcherService;
use App\Services\ProfileFetcherService;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        PlaceholderApiInterface::class => PlaceholderApiService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(PlaceholderApiService::class)
            ->needs(Client::class)
            ->give(fn (): Client => new Client(['base_uri' => Config::get('placeholder-api.base_url')]));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('profile', function (string $id): ProfileData {
            return $this->app->get(ProfileRepository::class)->find((int) $id);
        });
        Route::bind('post', function (string $id): PostData {
            return $this->app->get(PostRepository::class)->find((int) $id);
        });

        $this->app->bindMethod([FetchPosts::class, 'handle'], function (FetchPosts $job, Application $app): void {
            $job->handle(
                $app->make(PostFetcherService::class),
                $app->make(PostRepository::class),
                $app->make(ProfileRepository::class),
            );
        });
        $this->app->bindMethod([FetchProfiles::class, 'handle'], function (FetchProfiles $job, Application $app): void {
            $job->handle(
                $app->make(ProfileFetcherService::class),
                $app->make(ProfileRepository::class),
            );
        });
    }
}
