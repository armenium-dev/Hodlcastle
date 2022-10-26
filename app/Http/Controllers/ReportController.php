<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\Campaign;
use App\Models\Leaderboard;
use App\Models\Result;
use App\Repositories\CampaignRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\DomainRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends AppBaseController{

	private $campaignRepository;
	private $domainRepository;
	private $companyRepository;

	public function __construct(CampaignRepository $campaignRepo, DomainRepository $domainRepo, CompanyRepository $companyRepo){
		parent::__construct();

		$this->companyRepository = $companyRepo;
		$this->campaignRepository = $campaignRepo;
		$this->domainRepository   = $domainRepo;
	}

	public function index(Request $request){
		$this->companyRepository->pushCriteria(new RequestCriteria($request));
		$companies = $this->companyRepository->all();

		return view('report.index')->with(compact('companies'));
	}

	public function ajaxGetChartContent(Request $request){
		$res = ['error' => 1, 'content' => ''];

		$start_date = $request->post('start_date');
		$end_date = $request->post('end_date');

		if(!empty($start_date) && !empty($end_date)){
			$res['error'] = 0;
			$res['content'] = $this->getReportData($request);
		}

		return response()->json($res);
	}

	private function getReportData(Request $request){
		#dd($request->post());
		#$this->campaignRepository->pushCriteria(new RequestCriteria($request));

		if(Auth::user()){
			$start_date = $request->post('start_date');
			$end_date = $request->post('end_date');
			$company_id = (!is_null($request->post('company'))) ? $request->post('company') : Auth::user()->company->id;

			$query = $this->campaignRepository
				#->with(['results'])
				->where(['company_id' => $company_id]);

			if(!empty($start_date) && empty($end_date)){
				$start_date = Carbon::parse(str_replace('/', '.', $start_date))->format('Y-m-d');
				$query->where('created_at', '>=', $start_date);
			}elseif(empty($start_date) && !empty($end_date)){
				$end_date = Carbon::parse(str_replace('/', '.', $end_date))->format('Y-m-d');
				$query->where('created_at', '<=', $end_date);
			}elseif(!empty($start_date) && !empty($end_date)){
				$start_date = Carbon::parse(str_replace('/', '.', $start_date))->format('Y-m-d');
				$end_date = Carbon::parse(str_replace('/', '.', $end_date))->format('Y-m-d');
				$query->whereBetween('created_at', [$start_date, $end_date]);
			}

			$campaigns = $query->get()->sortByDesc('created_at');

			#dd($campaigns->toArray());

			$campaigns->each(function($campaign){
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
				#dump($campaign->toArray());
			});
			#dd($campaigns->count());

			if($campaigns->count() > 6){
				$campaigns_for_table = $campaigns->take(10);
			}else{
				$campaigns_for_table = $campaigns;
			}

			$labels = $campaigns_for_table->sortBy('created_at')->pluck('name');
			$len = 15;
			foreach($labels as $k => $label){
				$label = str_replace(['(PUBLIC)', 'Scenario:'], '', $label);
				$label = trim($label);
				if(strlen($label) > $len){
					$label = substr($label, 0, $len);
				}
				$labels[$k] = trim($label);
			}
			#dd($labels);
		}

		#$pdf_url = $this->createPDF($campaigns, $campaigns_for_table, $labels);
		#dd($pdf_url);

		return view('report.chart')->with(compact('campaigns', 'campaigns_for_table', 'labels'))->render();
	}

	public function ajaxGeneratePDF(Request $request){
		$res = ['error' => 1, 'filename' => '', 'link' => ''];

		if(Auth::user()){
			$image = $request->post('chart_image_data');
			$start_date = $request->post('start_date');
			$end_date = $request->post('end_date');
			$company_id = (!is_null($request->post('company'))) ? $request->post('company') : Auth::user()->company->id;

			$company = $this->companyRepository->findWhere(['id' => $company_id])->first();

			if(!empty($start_date) && empty($end_date)){
				$start_date = Carbon::parse(str_replace('/', '.', $start_date))->format('Y-m-d');
			}elseif(empty($start_date) && !empty($end_date)){
				$end_date = Carbon::parse(str_replace('/', '.', $end_date))->format('Y-m-d');
			}elseif(!empty($start_date) && !empty($end_date)){
				$start_date = Carbon::parse(str_replace('/', '.', $start_date))->format('Y-m-d');
				$end_date = Carbon::parse(str_replace('/', '.', $end_date))->format('Y-m-d');
			}

			$data = [
				'company_name' => $company->name,
				'company_logo' => ($company->logo ? url($company->logo->crop(100, 100, true)) : url('/public/img/logo.png')),
				'report_date' => date('Y-m-d'),
				'start_date' => $start_date,
				'end_date' => $end_date,
				'image' => $image,
			];
			#dd($data);

			$res['link'] = $this->createPDF($data);
			$res['filename'] = basename($res['link']);
			$res['error'] = 0;
		}

		return response()->json($res);
	}

	private function createPDF($data){
		if(!is_dir(public_path('downloads')))
			mkdir(public_path('downloads'), 0755);

		$date = date('Y-m-d-H-i-s');
		$file = sprintf('%s-%s.pdf', $date, 'report');
		$pdf_file_path_part = sprintf('%s%s%s', 'downloads', DIRECTORY_SEPARATOR, $file);

		$dompdf = PDF::loadView('report.test', compact('data'));
		$dompdf->save(public_path($pdf_file_path_part));

		return url($pdf_file_path_part);
	}

	#==================================================================

	/**
	 * FOR TESTS
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function ajaxGeneratePDF2(Request $request){
		#include_once(app_path("Helpers/charts4php/config.php"));
		include_once(app_path("Helpers/charts4php/lib/inc/chartphp.php"));
		include(app_path("Helpers/charts4php/example_data.php"));

		$res = ['error' => 1, 'filename' => '', 'link' => ''];

		// Vertical
		$p = new \chartphp();

		$p->data = $bar_grouped_chart_data;
		$p->chart_type = "bar-grouped";

		// Common Options
		$p->title = "Grouped Bar Chart";
		$p->xlabel = "Vehicles";
		$p->ylabel = "Frequency";
		$p->series_label = array('Quarter 1','Quarter 2','Quarter 3','Quarter 4');

		$data = $p->render('c1');

		$res['link'] = $this->createPDF($data);
		$res['filename'] = basename($res['link']);
		$res['error'] = 0;

		return response()->json($res);
	}


}
