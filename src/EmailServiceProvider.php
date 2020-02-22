<?php

namespace TwinDots\EmailService;

use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      $this->publishes([
         __DIR__.'/Config/email_service.php' => config_path('email_service.php'),
         __DIR__.'/Views/email_service' => resource_path('views/email_service'),
      ]);
    }
}
