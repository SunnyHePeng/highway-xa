<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Event;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
       'App\Events\FileUploadDir' => [
            'App\Listeners\FileUploadDirListener',
        ],
        'App\Events\GetFileInfo' => [
            'App\Listeners\GetFileInfoListener',
        ],
    ];
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    /*protected $subscribe = [
        'App\Listeners\FileUploadEventListener',
    ];*/
    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
        //Event::listen('GetUploadsDir', 'FileUploadEventListener');
        //
    }

    
}
