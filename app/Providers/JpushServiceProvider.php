<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

/**
 * 未发现此服务
 */
class JpushServiceProvider extends ServiceProvider
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
        App::bind('jpush', function()
		{
		    return new App\Jpush\Jpush;
		});
    }
}
