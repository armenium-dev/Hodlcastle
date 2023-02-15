<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Exception;
use Spatie\Permission\Exceptions\UnauthorizedException;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller{

	public function __construct(Request $request = null){
		#dump($request->path());
		if(!is_null($request)){
			if(!$request->ajax()){
				#$sg = $request->segments();
				#$path = end($sg);
				$path = $request->segment(1);
				if(is_null($path)){
					$path = $request->path();
				}
				#dump(end($sg));

				if($path == '/'){
					$path = 'dashboard';
				}

				if(Auth::user() && !Auth::user()->hasRole('captain') && !PermissionHelper::companyAccessToSection($path)){
					abort(401, 'Unauthorized action!');
				}
			}
		}

		//$this->middleware('auth');
	}

	public function sendResponse($result, $message){
		return Response::json(ResponseUtil::makeResponse($message, $result));
	}

	public function sendError($error, $code = 404){
		return Response::json(ResponseUtil::makeError($error), $code);
	}

	public function sendUnauthorized($roles = []){
		throw UnauthorizedException::forRoles($roles);
	}
}
