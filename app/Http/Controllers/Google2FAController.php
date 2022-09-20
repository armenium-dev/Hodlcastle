<?php namespace App\Http\Controllers;

use Crypt;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;

class Google2FAController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    public function index()
    {
        return view('profile/2fa');
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        //generate new secret
        $secret = $this->generateSecret();

        //get user
        $user = $request->user();

        $google2fa = new Google2FA();

        //encrypt and then save secret
        $secretKey = $google2fa->generateSecretKey();//dd($secretKey, $secret);
        $user->google2fa_secret = Crypt::encrypt($secretKey);
        $user->save();

        //$secretKey = $google2fa->generateSecretKey(16, $user->id);

        //generate image for QR barcode
//        $inlineUrl = $google2fa->getQRCodeInline(
//            'Phishmanager',
//            'alchemistt@verifysecurity.nl',
//            $secretKey
//        );
        $google2fa->setAllowInsecureCallToGoogleApis(true);

        $inlineUrl = $google2fa->getQRCodeGoogleUrl(
            'PhishManager',
            $user->email,
            $secretKey
        );
//        $imageDataUri = Google2FA::getQRCodeInline(
//            $request->getHttpHost(),
//            $user->email,
//            $secret,
//            200
//        );

        return view('profile.2fa.enableTwoFactor', [
            'image' => $inlineUrl,
            'secret' => $secretKey,
        ]);
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        //make secret column blank
        $user->google2fa_secret = null;
        $user->save();

        return view('profile.2fa.disableTwoFactor');
    }

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes);
    }
}
