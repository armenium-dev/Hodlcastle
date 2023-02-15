<?php
# TODO: Необходимо разработать и завершить интерфейс Settings для капитанов.


namespace App\Http\Controllers;

use App\Models\AppSections;
use App\Models\CompanyProfileRules;
use App\Models\CompanyProfiles;
use App\Models\CompanyProfileTerms;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Flash;
use Response;

class SettingsController extends AppBaseController {

	public function __construct(Request $request){
		parent::__construct($request);
	}

	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$options = Settings::query()->orderBy('id', 'desc')->get();

		return view('settings.index', ['options' => $options]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		$model = Settings::findOrFail($id);

		switch($model->name){
			case "blacklisted_sms_terms":
			case "ship_engine_services_options":
				$model->value = $this->_arrayToHtmlTable(json_decode($model->value, true));
				#$model->value = '<pre>'.print_r(json_decode($model->value, true), true).'</pre>';
				break;
		}

		return view('settings.show', ['model' => $model]);
	}

	private function _arrayToHtmlTable($data){
		$html = [];
		$html[] = '<table class="table table-striped">';
		foreach($data as $k => $v){
			$html[] = sprintf('<tr><td>%s</td></tr>', implode('</td><td>', $v));
		}
		$html[] = '</table>';

		return implode(PHP_EOL, $html);
	}

	/** CUSTOM METHODS **/

	public function blacklistedSmsTermsStore(Request $request){
		$res = ['error' => 0, 'message' => 'Terms saved.'];

		$terms = $request->input('terms');
		$terms = array_filter($terms);

		$opt = Settings::where(['option_key' => 'blacklisted_sms_terms'])->first();

		if(is_null($opt)){
			$opt = new Settings();
			$opt->option_key = 'blacklisted_sms_terms';
		}

		$opt->option_value = json_encode($terms);
		$opt->save();

		return response()->json($res);
	}

	public function companyProfiles(){
		$profiles = CompanyProfiles::all();
		$profiles = $profiles->toArray();

		if(!count($profiles)){
			$profiles = [];
			$profiles[0]['id'] = null;
			$profiles[0]['name'] = '';
		}

		return view('settings.company_profiles', compact('profiles'));
	}

	public function companyProfilesStore(Request $request){
		#dd($request->input());
		$ids = $request->input('ids');
		$names = $request->input('names');

		$data = [];
		foreach($ids as $k => $v){
			$data[$k] = [
				'id' => $v,
				'name' => $names[$k],
			];
		}

		if(!empty($data)){
			foreach($data as $item){
				if(is_null($item['id'])){
					CompanyProfiles::create($item);
				}else{
					$model = CompanyProfiles::find($item['id']);
					$model->name = $item['name'];
					$model->save();
				}
			}
		}

		Flash::success('Profiles saved successfully.');

		return redirect(route('settings.company_profiles.index'));
	}

	public function companyProfileTerms(){
		$terms = CompanyProfileTerms::all();
		$terms = $terms->toArray();

		if(!count($terms)){
			$terms = [];
			$terms[0]['id'] = null;
			$terms[0]['name'] = '';
			$terms[0]['slug'] = '';
		}

		return view('settings.company_profiles_terms', compact('terms'));
	}

	public function companyProfileTermsStore(Request $request){
		$ids = $request->input('ids');
		$names = $request->input('names');
		$slugs = $request->input('slugs');

		$data = [];
		foreach($ids as $k => $v){
			$data[$k] = [
				'id' => $v,
				'name' => $names[$k],
				'slug' => $slugs[$k],
			];
		}

		if(!empty($data)){
			foreach($data as $item){
				if(is_null($item['id'])){
					CompanyProfileTerms::create($item);
				}else{
					$model = CompanyProfileTerms::find($item['id']);
					$model->name = $item['name'];
					$model->slug = $item['slug'];
					$model->save();
				}
			}
		}

		Flash::success('Terms saved successfully.');

		return redirect(route('settings.company_profiles.terms'));
	}

	public function companyProfileRules(){
		$profiles = CompanyProfiles::all();
		$profiles = $profiles->toArray();

		$terms = CompanyProfileTerms::all();
		$terms = $terms->toArray();

		$rules = CompanyProfileRules::all();

		if($rules->count()){
			$_rules = [];
			foreach($rules as $rule)
				$_rules[$rule->profile_id][$rule->term_id] = $rule->active;

			$rules = $_rules;
		}

		return view('settings.company_profiles_rules', compact('profiles', 'terms', 'rules'));
	}

	public function companyProfileRulesStore(Request $request){
		CompanyProfileRules::query()->update(['active' => 0]);

		$rules = $request->input('rules');

		if(!is_null($rules)){
			foreach($rules as $profile_id => $profile_data){
				foreach($profile_data as $term_id => $active){
					$model = CompanyProfileRules::where(['profile_id' => $profile_id, 'term_id' => $term_id, 'active' => 0])->first();
					if(is_null($model)){
						CompanyProfileRules::create(['profile_id' => $profile_id, 'term_id' => $term_id, 'active' => 1]);
					}else{
						$model->active = $active;
						$model->save();
					}
					unset($model);
				}
			}
			CompanyProfileRules::where('active', 0)->delete();
		}

		Flash::success('Rules saved successfully.');

		return redirect(route('settings.company_profiles.rules'));
	}

	public function companyProfileDestroy(Request $request){
		$res = ['error' => 0, 'message' => 'Entry removed.'];


		$id = $request->input('id');
		$type = $request->input('type');

		if(intval($id)){
			if($type == 'term'){
				$model = CompanyProfileTerms::find($id);
				$model->delete();
			}elseif($type == 'profile'){
				$model = CompanyProfiles::find($id);
				$model->delete();
			}
		}else{
			$res['error'] = 1;
			$res['message'] = 'Entry not found';
		}

		return response()->json($res);
	}

}
