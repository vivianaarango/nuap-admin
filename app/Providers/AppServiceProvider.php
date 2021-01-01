<?php

namespace App\Providers;

use App\Libraries\Responders\ArrayResponse;
use App\Libraries\Responders\Contracts\ArrayResponseInterface;
use App\Libraries\Responders\Contracts\JsonApiResponseInterface;
use App\Libraries\Responders\JsonApiResponse;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(JsonApiResponseInterface::class, JsonApiResponse::class);
        $this->app->bind(ArrayResponseInterface::class, ArrayResponse::class);
    }
}
