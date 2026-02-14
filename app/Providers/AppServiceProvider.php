<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\VendorRepositoryInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\VendorRepository;
use App\Repositories\BlogRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CouponRepository;
use App\Services\ImageUploadService;
use App\Services\ReviewManagementService;
use App\Services\SearchFilterService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register base repository
        $this->app->bind(RepositoryInterface::class, BaseRepository::class);

        // Register specific repositories
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);

        // Register shared services
        $this->app->singleton(ImageUploadService::class);
        $this->app->singleton(ReviewManagementService::class);
        $this->app->singleton(SearchFilterService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Observers
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
        \App\Models\Product::observe(\App\Observers\ProductObserver::class);

        // Register Policies
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Blog::class, \App\Policies\BlogPolicy::class);
    }
}
