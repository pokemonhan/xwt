<?php

namespace App\Providers;

use App\Events\IssueGenerateEvent;
use App\Listeners\BackendLogMonologEventListener;
use App\Listeners\FrontendLogMonologEventListener;
use App\Listeners\IssueGenerateEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        IssueGenerateEvent::class => [
            IssueGenerateEventListener::class
        ],
    ];

    protected $subscribe = [
        FrontendLogMonologEventListener::class,
        BackendLogMonologEventListener::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
