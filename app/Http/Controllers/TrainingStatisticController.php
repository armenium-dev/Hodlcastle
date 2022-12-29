<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\DownloadFiles;
use App\Models\Leaderboard;
use App\Models\TrainingExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\TrainingStatistic;
use App\Repositories\TrainingStatisticRepository;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingStatisticController extends AppBaseController{
	private $trainingStatisticRepository;

	public function __construct(Request $request, TrainingStatisticRepository $trainingStatisticRepo){
		parent::__construct($request);

		$this->trainingStatisticRepository = $trainingStatisticRepo;
	}

	public function index(Request $request){
		$years = DB::table('training_statistics')
			->whereNotNull('start_training')
			->groupBy('value')
			->orderBy('value')
			->get([DB::raw('EXTRACT(YEAR FROM start_training) AS value')])
			->toArray();

		return view('training_statistics.index', ['years' =>$years]);
	}

	public function table(Request $request){
		if($request->ajax()){

			$this->trainingStatisticRepository->pushCriteria(BelongsToCompanyCriteria::class);
			$trainingStatistic = $this->trainingStatisticRepository->all();
			if(!is_null($request->post('columns')[3]['search']['value'])){
				$trainingStatistic->where(DB::raw('EXTRACT(YEAR FROM start_training)'), $request->post('columns')[3]['search']['value']);
			}
			#dd($request->post('columns')[3]['search']['value']);

			return Datatables::of($trainingStatistic)
				->setRowAttr([
					'data-year' => function($row){
						$c = new Carbon($row->start_training);
						$year = $c->year;
						return $year;
					}
				])
				->addIndexColumn()
				->addColumn('recipient', function($row){
					return $row->recipient ? $row->recipient->first_name.' '.$row->recipient->last_name : '';
				})
				->addIndexColumn()
				->addColumn('recipient_email', function($row){
					return $row->recipient ? $row->recipient->email : '';
				})
				->addIndexColumn()
				->addColumn('company', function($row){
					return $row->company ? $row->company->name : '';
				})
				->addIndexColumn()
				->addColumn('time', function($row){

					$diff = '';

					if($row->start_training && $row->finish_training){
						$from = new Carbon($row->start_training);
						$till = new Carbon($row->finish_training);
						$diff = $till->diffInMinutes($from).' min';
					}

					return $diff;

				})
				->make(true);
		}

		return false;
	}

	public function exportIndex(Request $request){
		#$this->trainingStatisticRepository->pushCriteria(BelongsToCompanyCriteria::class);
		#$this->trainingStatisticRepository->where(['is_finish' => 0]);
		#$trainingStatistic = $this->trainingStatisticRepository->all();
		#dd($trainingStatistic);

		$rows = $this->getData($request->toArray());
		$modules = $rows->pluck('m_name', 'm_id');
		$companies = $rows->pluck('c_name', 'company_id');

		return view('training_statistics.export')->with(compact('rows', 'modules', 'companies'));
	}

	public function ajaxSort(Request $request){
		$res = ['error' => 1, 'content' => ''];

		$rows = $this->getData($request->toArray());
		#sdd($leaderboard->toArray());

		if(!empty($rows)){
			$res['error'] = 0;
			if($request->get('export') == 'true'){
				$res['link'] = $this->exportToCSV($rows);
			}else{
				$res['content'] = view('training_statistics.export-table-rows')->with(compact('rows'))->render();
			}
		}

		return response()->json($res);
	}

	private function getData($params){

		if(Auth::user() && Auth::user()->company){
			DB::statement("SET SESSION sql_mode = sys.list_drop(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY')");

			$query = TrainingExport::query();

			if(Auth::user()->hasRole('customer')){
				$query->where(['company_id' => Auth::user()->company->id]);
			}

			if(isset($params['form_data'])){
				$form_data = [];
				parse_str($params['form_data'], $form_data);

				if(!empty($form_data['start_date']) && empty($form_data['end_date'])){
					$start_date = Carbon::parse(str_replace('/', '.', $form_data['start_date']))->format('Y-m-d');
					$query->where('start_training', '>=', $start_date);
				}elseif(empty($form_data['start_date']) && !empty($form_data['end_date'])){
					$end_date = Carbon::parse(str_replace('/', '.', $form_data['end_date']))->format('Y-m-d');
					$query->where('finish_training', '<=', $end_date);
				}elseif(!empty($form_data['start_date']) && !empty($form_data['end_date'])){
					$start_date = Carbon::parse(str_replace('/', '.', $form_data['start_date']))->format('Y-m-d');
					$end_date = Carbon::parse(str_replace('/', '.', $form_data['end_date']))->format('Y-m-d');
					$query->where('start_training', '>=', $start_date);
					$query->where('finish_training', '<=', $end_date);
				}

				if(intval($form_data['company']) > -1){
					$query->where(['company_id' => intval($form_data['company'])]);
				}

				if(intval($form_data['is_finish']) > -1){
					$query->where(['is_finish' => intval($form_data['is_finish'])]);
				}

				if(intval($form_data['module']) > -1){
					$query->where(['m_id' => intval($form_data['module'])]);
				}
			}

			if(isset($params['dir']) && $params['dir'] != 'reset'){
				$query->orderBy($params['name'], $params['dir']);
			}

			return $query->get();
		}

		return [];
	}

	private function exportToCSV($rows){
		$csv_path = public_path('csv');

		if(!is_dir($csv_path)){
			@mkdir($csv_path, 0755);
		}

		$csv_file_name = date('Y-m-d-H-i-s').'-training-statistics.csv';

		$headers = [
			'Module',
			'Recipient',
			'Email',
			'Company',
			'Start',
			'Finish',
			'Time spend',
		];

		$file_path = $csv_path.'/'.$csv_file_name;

		$fp = fopen($file_path, 'w');
		fputcsv($fp, $headers);

		foreach($rows as $fields){
			$data = $fields->toArray();
			$data['first_name'] = $data['first_name'].' '.$data['last_name'];

			unset($data['company_id'], $data['m_id'], $data['ts_id'], $data['code'],
				$data['recipient_id'], $data['public'], $data['last_name'], $data['is_finish']);

			if(is_null($data['timespend'])){
				$data['timespend'] = 0;
			}
			$data['timespend'] .= ' min';

			fputcsv($fp, $data);
		}

		fclose($fp);

		$save_file_url = url(sprintf('%s%s%s', 'csv', '/', $csv_file_name));

		DownloadFiles::create(['file' => $file_path]);

		return $save_file_url;
	}

}
