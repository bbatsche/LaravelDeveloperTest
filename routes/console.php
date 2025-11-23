<?php

declare(strict_types=1);

use App\Jobs\FetchProfiles;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('fetch:profiles', function (): void {
    FetchProfiles::dispatch();
});
