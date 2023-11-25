<?php

namespace App\Providers;

use App\Repositories\BoardRepository;
use App\Repositories\Impl\BoardRepositoryImpl;
use App\Services\BoardService;
use App\Services\Impl\BoardServiceImpl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Model::preventLazyLoading(!$this->app->isProduction());
        
        if ($this->app->isLocal()) $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

        $this->app->singleton(BoardRepository::class, BoardRepositoryImpl::class);

        $this->app->singleton(BoardService::class, BoardServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
