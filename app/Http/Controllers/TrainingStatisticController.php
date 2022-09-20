<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\TrainingStatistic;
use App\Repositories\TrainingStatisticRepository;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class TrainingStatisticController extends AppBaseController
{
    private $trainingStatisticRepository;

    public function __construct(TrainingStatisticRepository $trainingStatisticRepo)
    {
        parent::__construct();

        $this->trainingStatisticRepository = $trainingStatisticRepo;
    }

    public function index(Request $request)
    {
        return view('training_statistics.index');
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {

            $this->trainingStatisticRepository->pushCriteria(BelongsToCompanyCriteria::class);
            $trainingStatistic = $this->trainingStatisticRepository->all();

            return Datatables::of($trainingStatistic)

                ->addIndexColumn()
                ->addColumn('recipient', function($row){
                    return $row->recipient ? $row->recipient->first_name . ' ' . $row->recipient->last_name : '';
                })

                ->addIndexColumn()
                ->addColumn('recipient_email', function($row){
                    return $row->recipient ? $row->recipient->email: '';
                })

                ->addIndexColumn()
                ->addColumn('company', function($row){
                    return $row->company ? $row->company->name : '';
                })


                ->addIndexColumn()
                ->addColumn('time', function($row){

                    $diff = '';

                    if ($row->start_training && $row->finish_training) {
                        $from = new Carbon($row->start_training);
                        $till = new Carbon($row->finish_training);
                        $diff = $till->diffInMinutes($from) . ' min';
                    }

                    return $diff;

                })

                ->make(true);
        }

        return false;
    }
}