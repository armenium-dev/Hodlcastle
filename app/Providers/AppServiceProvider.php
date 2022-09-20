<?php

namespace App\Providers;

use App\Helpers\SwiftMailPlugin;
use App\Helpers\SwiftMailAttachPlugin;
use App\User;
use Illuminate\Support\ServiceProvider;
use Validator;
use PragmaRX\Google2FA\Google2FA;
use Crypt;
use Cache;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['mailer']->getSwiftMailer()->registerPlugin(new SwiftMailPlugin());
        $this->app['mailer']->getSwiftMailer()->registerPlugin(new SwiftMailAttachPlugin());

        Validator::extend('valid_token', function ($attribute, $value, $parameters, $validator) {
            $user = User::find(\Session::get('2fa:user:id'));

            $google2fa = new Google2FA();
            if ($user->google2fa_secret) {
                $secret = Crypt::decrypt($user->google2fa_secret);

                if ($google2fa->verifyKey($secret, $value) == false) {
                    return false;
                }
            }

            return true;
        });
        Validator::extend('not_used_token', function ($attribute, $value, $parameters, $validator) {
            $user = User::find(\Session::get('2fa:user:id'));

            if ($user->google2fa_secret) {
                if (Cache::has($user->id . ':' . $value)) {
                    return false;
                }
            }

            return true;
        });
        Relation::morphMap([
            'video' => \App\Models\PageVideo::class,
            'text' => \App\Models\PageText::class,
            'quiz' => \App\Models\PageQuiz::class
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
