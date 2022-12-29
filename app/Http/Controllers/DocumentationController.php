<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentationController extends AppBaseController{

	public function __construct(Request $request){
		parent::__construct($request);
	}

	public function index(){
		#return view('documentation.index');
	}
}
