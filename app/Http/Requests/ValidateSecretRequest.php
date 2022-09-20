<?php namespace App\Http\Requests;

use Cache;
use Crypt;
use Dotenv\Exception\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory as ValidatonFactory;
use Flash;
use Illuminate\Foundation\Http\FormRequest;

class ValidateSecretRequest extends FormRequest
{
    /**
     *
     * @var \App\User
     */
    private $user;

    /**
     * Create a new FormRequest instance.
     *
     * @param \Illuminate\Validation\Factory $factory
     * @return void
     */
    public function __construct(ValidatonFactory $factory)
    {
        parent::__construct();

        $this->redirect = route('profile.2fa.validate');

        $user = User::find(\Session::get('2fa:user:id'));

        $google2fa = new Google2FA();
        if ($user->google2fa_secret) {
            $secret = Crypt::decrypt($user->google2fa_secret);

            if ($google2fa->verifyKey($secret, $_POST['totp']) == false) {
                Flash::error('Not a valid token');
                //dd($_POST['totp'], 1);
                $this->redirect = route('profile.2fa.validate');
            }
//dd(Cache::has($user->id . ':' . $_POST['totp']), Cache::all());

            if (Cache::has($user->id . ':' . $_POST['totp'])) {
                Flash::error('Cannot reuse token');
                //dd($_POST['totp'], 2);
                $this->redirect = route('profile.2fa.validate');
            }
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        try {
            $this->user = User::findOrFail(
                session('2fa:user:id')
            );
        } catch (Exception $exc) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'totp' => 'bail|required|digits:6|valid_token|not_used_token',
        ];
    }
}
