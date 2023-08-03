<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\SendVerifyEmail;
use App\Models\User;
use App\Observers\UserRegisteredObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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

        /*? Event ve Listener  olarak tanımlama
          UserRegistered::class => [
            SendVerifyEmail::class
        ]*/
    ];

    /*** Send mail with observers */
    protected $observers = [
        User::class => UserRegisteredObserver::class
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        /*? Alternative usage for observers
          User::observe(UserRegisteredObserver::class);
         * */
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
