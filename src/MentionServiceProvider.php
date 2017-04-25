<?php

namespace Kingsley\Mentions;

use Illuminate\Support\ServiceProvider;

class MentionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/mentions.php' => config_path('mentions.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/mentions.php', 'mentions');
    }
}
