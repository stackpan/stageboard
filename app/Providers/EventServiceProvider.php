<?php

namespace App\Providers;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Observers\BoardObserver;
use App\Observers\CardObserver;
use App\Observers\ColumnObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }

    protected function discoverEventsWithin(): array
    {
        return [
            $this->app->path('Listeners'),
        ];
    }

    protected $observers = [
        Board::class => [BoardObserver::class],
        Column::class => [ColumnObserver::class],
        Card::class => [CardObserver::class]
    ];
}
