<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\EmailTemplateRepository;


class EmailTemplatesServiceProvider extends ServiceProvider
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
        $this->app->bind('EmailTemplates', function($app){
            return new \App\Helpers\EmailTemplates(
                new EmailTemplateRepository($app),
                $app->make('cache.store'),
                \Config::get('laravel-email-templates.css_file')
            );
        });

//        $this->app['emailtemplates'] = $this->app->share(function($app)
//        {
//            return new \App\Helpers\EmailTemplates;
//        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('EmailTemplates', 'App\Facades\EmailTemplates');
        });
    }
}