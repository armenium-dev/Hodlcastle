<?php

namespace App\Http\Controllers;

use App\Models\Options;
use Illuminate\Http\Request;
use Flash;
use Response;

class OptionsController extends AppBaseController {

	public function __construct(){
		parent::__construct();
	}

	public function blacklistedSmsTermsStore(Request $request){
		$res = ['error' => 0, 'message' => 'Terms saved.'];

		$terms = $request->input('terms');
		$terms = array_filter($terms);

		$opt = Options::where(['option_key' => 'blacklisted_sms_terms'])->first();

		if(is_null($opt)){
			$opt = new Options();
			$opt->option_key = 'blacklisted_sms_terms';
		}

		$opt->option_value = json_encode($terms);
		$opt->save();

		return response()->json($res);
	}


}
