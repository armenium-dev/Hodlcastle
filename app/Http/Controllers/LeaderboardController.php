<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\Campaign;
use App\Models\Leaderboard;
use App\Repositories\CampaignRepository;
use App\Repositories\DomainRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends AppBaseController{

	private $campaignRepository;
	private $domainRepository;

	public function __construct(CampaignRepository $campaignRepo, DomainRepository $domainRepo){
		parent::__construct();

		$this->campaignRepository = $campaignRepo;
		$this->domainRepository   = $domainRepo;
	}

	public function index(Request $request){
		#$this->campaignRepository->pushCriteria(new RequestCriteria($request));

		#dd(Auth::user()->company->id);

		/*if(Auth::user() && Auth::user()->company){
			#$campaigns = $this->campaignRepository->pushCriteria(BelongsToCompanyCriteria::class)->all()->sortByDesc('id')->pluck('id')->toArray();

			DB::statement("SET SESSION sql_mode = sys.list_drop(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY')");

			$query = Leaderboard::query();
			$query->where(['company_id' => Auth::user()->company->id]);
			$query->orderByDesc('send_date');
			$leaderboard = $query->get();

			#dd($leaderboard);
		}*/

		$leaderboard = $this->getLeaderboardData(['name' => 'send_date', 'dir' => 'desc']);

		return view('leaderboard.index')->with(compact('leaderboard'));
	}

	# TODO for get requests
	public function sort(Request $request){

	}

	public function ajaxSort(Request $request){
		$res = ['error' => 1, 'content' => ''];

		$leaderboard = $this->getLeaderboardData($request->toArray());
		#sdd($leaderboard->toArray());

		if(!empty($leaderboard)){
			$res['error'] = 0;
			if($request->get('export') == 'true'){
				$res['link'] = $this->exportToCSV($leaderboard);
			}else{
				$res['content'] = view('leaderboard.table-rows')->with(compact('leaderboard'))->render();
			}
		}

		return response()->json($res);
	}

	private function getLeaderboardData($params){

		if(Auth::user() && Auth::user()->company){
			DB::statement("SET SESSION sql_mode = sys.list_drop(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY')");

			$query = Leaderboard::query();
			$query->where(['company_id' => Auth::user()->company->id]);

			if(isset($params['form_data'])){
				$form_data = [];
				parse_str($params['form_data'], $form_data);

				if(!empty($form_data['start_date']) && empty($form_data['end_date'])){
					$start_date = Carbon::parse(str_replace('/', '.', $form_data['start_date']))->format('Y-m-d');
					$query->where('send_date', '>=', $start_date);
				}elseif(empty($form_data['start_date']) && !empty($form_data['end_date'])){
					$end_date = Carbon::parse(str_replace('/', '.', $form_data['end_date']))->format('Y-m-d');
					$query->where('send_date', '<=', $end_date);
				}elseif(!empty($form_data['start_date']) && !empty($form_data['end_date'])){
					$start_date = Carbon::parse(str_replace('/', '.', $form_data['start_date']))->format('Y-m-d');
					$end_date = Carbon::parse(str_replace('/', '.', $form_data['end_date']))->format('Y-m-d');
					$query->whereBetween('send_date', [$start_date, $end_date]);
				}
			}

			if($params['dir'] != 'reset'){
				$query->orderBy($params['name'], $params['dir']);
			}

			return $query->get();
		}

		return [];
	}

	private function exportToCSV($leaderboard){
		$csv_path = public_path('csv');

		if(!is_dir($csv_path)){
			@mkdir($csv_path, 0755);
		}

		$csv_file_name = date('Y-m-d-H-i-s').'-leaderboard.csv';

		$headers = [
			'Send date',
			'First Name',
			'Last Name',
			'Email',
			'Phone number',
			'Mails sent',
			'Reported phishes',
			'Phished',
			'Phish rate',
			'Reporting rate',
			'SMS sent',
			'Smished',
			'Smish rate',
			'Department',
			'Location',
		];

		$fp = fopen($csv_path.'/'.$csv_file_name, 'w');
		fputcsv($fp, $headers);

		foreach($leaderboard as $fields){
			$data = $fields->toArray();
			#dd($data);
			unset($data['company_id'], $data['campaign_id'], $data['recipient_id']);

			if(is_null($data['smish_rate'])){
				$data['smish_rate'] = 0;
			}

			if(!empty($data['phone'])){
				$data['phone'] = '"'.$data['phone'].'"';
			}
			$data['phish_rate'] .= '%';
			$data['reporting_rate'] .= '%';
			$data['smish_rate'] .= '%';

			fputcsv($fp, $data);
		}

		fclose($fp);

		$save_file_url = url(sprintf('%s%s%s', 'csv', '/', $csv_file_name));

		return $save_file_url;
	}

	/** OLD, TO BE REMOVED **/
	public function _index(Request $request){
		$this->campaignRepository->pushCriteria(new RequestCriteria($request));

		if(Auth::user() && Auth::user()->company){
			$campaigns = $this->campaignRepository->pushCriteria(BelongsToCompanyCriteria::class)->all()->sortByDesc('created_at');
			$campaigns->each(function($campaign){
				#dump($campaign->recipients);
				$campaign->results->each(function($result){
					dump($result->events);

				});

				/*
				$sent       = $campaign->countResults('sent');
				$fake_auth  = $campaign->countResults('fake_auth');
				$open       = $campaign->countResults('open');
				$click      = $campaign->countResults('click');
				$report     = $campaign->countResults('report');
				$attachment = $campaign->countResults('attachment');
				$smish      = $campaign->countResults('smish');

				if($sent){
					$campaign->sentsCount       = 100;
					$campaign->opensCount       = $open * 100 / $sent;
					$campaign->fake_auth        = $fake_auth * 100 / $sent;
					$campaign->clicksCount      = $click * 100 / $sent;
					$campaign->reportsCount     = $report * 100 / $sent;
					$campaign->attachmentsCount = $attachment * 100 / $sent;
					$campaign->smishsCount      = $smish * 100 / $sent;
				}else{
					$campaign->sentsCount       = 0;
					$campaign->opensCount       = 0;
					$campaign->fake_auth        = 0;
					$campaign->clicksCount      = 0;
					$campaign->reportsCount     = 0;
					$campaign->attachmentsCount = 0;
					$campaign->smishsCount      = 0;
				}
				*/
			});

			$campaigns_for_table = $campaigns->take(12);
		}else{
			$campaigns = $this->campaignRepository->all()->sortByDesc('created_at');
		}

		dd('END');

		$campaign_status = Campaign::STATUS_INACTIVE;

		return view('leaderboard.index')->with(compact('campaigns', 'campaigns_for_table', 'campaign_status'));
	}

}
