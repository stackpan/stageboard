<?php

namespace App\Providers;

use App\Repositories\BoardRepository;
use App\Repositories\CardRepository;
use App\Repositories\ColumnRepository;
use App\Repositories\Impl\BoardRepositoryImpl;
use App\Repositories\Impl\CardRepositoryImpl;
use App\Repositories\Impl\ColumnRepositoryImpl;
use App\Repositories\Impl\UserRepositoryImpl;
use App\Repositories\UserRepository;
use App\Services\BoardCollaborationService;
use App\Services\BoardService;
use App\Services\CardService;
use App\Services\ColumnService;
use App\Services\Impl\BoardCollaborationServiceImpl;
use App\Services\Impl\BoardServiceImpl;
use App\Services\Impl\CardServiceImpl;
use App\Services\Impl\ColumnServiceImpl;
use App\Services\Impl\UserServiceImpl;
use App\Services\UserService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Model::preventLazyLoading(!$this->app->isProduction());

        if ($this->app->isLocal()) $this->app->register(IdeHelperServiceProvider::class);

        $this->app->singleton(BoardRepository::class, BoardRepositoryImpl::class);
        $this->app->singleton(ColumnRepository::class, ColumnRepositoryImpl::class);
        $this->app->singleton(CardRepository::class, CardRepositoryImpl::class);
        $this->app->singleton(UserRepository::class, UserRepositoryImpl::class);

        $this->app->singleton(BoardService::class, BoardServiceImpl::class);
        $this->app->singleton(BoardCollaborationService::class, BoardCollaborationServiceImpl::class);
        $this->app->singleton(ColumnService::class, ColumnServiceImpl::class);
        $this->app->singleton(CardService::class, CardServiceImpl::class);
        $this->app->singleton(UserService::class, UserServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
