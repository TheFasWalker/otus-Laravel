<?php

namespace App\Providers;

use App\Domain\Product\Contracts\ProductRepositoryInterface;
use App\Infrastructure\Eloquent\Product\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }
}