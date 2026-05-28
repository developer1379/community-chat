<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\CategoryRepositoryInterface::class,
            \App\Repositories\CategoryRepository::class
        );
        $this->app->bind(
            \App\Repositories\ThreadRepositoryInterface::class,
            \App\Repositories\ThreadRepository::class
        );
        $this->app->bind(
            \App\Repositories\PostRepositoryInterface::class,
            \App\Repositories\PostRepository::class
        );
        $this->app->bind(
            \App\Repositories\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\ChatRepositoryInterface::class,
            \App\Repositories\ChatRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
