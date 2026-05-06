<?php

namespace App\Providers;

use App\Repositories\Interfaces\ProductoRepositoryInterface;
use App\Repositories\ProductoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProductoRepositoryInterface::class,
            ProductoRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
