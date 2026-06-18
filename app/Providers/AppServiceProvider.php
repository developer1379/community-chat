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
            \App\Repositories\Interfaces\CategoryRepositoryInterface::class,
            \App\Repositories\Eloquent\CategoryRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\ThreadRepositoryInterface::class,
            \App\Repositories\Eloquent\ThreadRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\PostRepositoryInterface::class,
            \App\Repositories\Eloquent\PostRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\ChatRepositoryInterface::class,
            \App\Repositories\Eloquent\ChatRepository::class
        );
    }

    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key');
                foreach ($settings as $key => $value) {
                    if (str_starts_with($key, 'firebase_')) {
                        $configKey = 'firebase.' . substr($key, 9);
                        config([$configKey => $value]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Silence exception if database/table is not ready during console executions
        }
    }
}
