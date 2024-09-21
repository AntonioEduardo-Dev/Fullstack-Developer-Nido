<?php

namespace App\Providers;

use App\Interfaces\Api\WordRepositoryInterface;
use App\Repositories\Api\WordRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WordRepositoryInterface::class, WordRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
