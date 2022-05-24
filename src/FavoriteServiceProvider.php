<?php

namespace Manzadey\LaravelFavorite;

use Illuminate\Support\ServiceProvider;

class FavoriteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() : void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_favorites_table.php' => database_path('migrations/' . date('Y_m_d_His') . '_create_favorites_table.php'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/favorite.php' => config_path('favorite.php'),
        ], 'config');
    }

    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/favorite.php', 'favorite');
    }
}
