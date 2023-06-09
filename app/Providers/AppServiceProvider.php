<?php

namespace App\Providers;

use App\Interface\ChatInterface;
use App\Reposetry\ChatReposetry;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ChatInterface::class,ChatReposetry::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
