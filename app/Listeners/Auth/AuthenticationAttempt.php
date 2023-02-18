<?php

namespace App\Listeners\Auth;

use App\Mail\FailedLoginAttempts;
use App\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthenticationAttempt
{
    use ThrottlesLogins;

    /**
     * Handle the event.
     * @param object $event
     * @return bool|void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle($event)
    {
        $email = $event->credentials['email'];
        $user = User::where('email', $email)->first();

        if (empty($user)) {
            return false;
        }

        if ($user->attempts_login >= User::MAX_FAILED_ATTEMPT_LOGIN) {
            try {
                Mail::to($email)->send(new FailedLoginAttempts($user));
            } catch (\Exception $e) {
                Log::channel('email')->error($e->getMessage());
            }

            throw ValidationException::withMessages([
                'attempts_login' => 'Error',
            ])->status(429);
        }

        $user->increment('attempts_login');
    }
}
