<?php
namespace App\Providers;

use App\Helpers\SwiftMailPlugin;
use Illuminate\Support\ServiceProvider;

class MailTrackerCustomServiceProvider extends ServiceProvider
{
    public function boot()
    {
        dd('hey');
        //$this->app['mailer']->getSwiftMailer()->registerPlugin(new SwiftMailPlugin());
    }
}