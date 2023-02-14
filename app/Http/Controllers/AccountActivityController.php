<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountActivitiesRequest;
use App\Models\DownloadFiles;
use App\Repositories\AccountActivitiesRepository;
use App\Repositories\CampaignRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class AccountActivityController extends AppBaseController
{
    private $campaignRepository;
    private $activitiesRepository;
    private $userRepository;

    /**
     * AccountActivityController constructor.
     * @param Request $request
     * @param CampaignRepository $campaignRepo
     * @param AccountActivitiesRepository $activitiesRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        Request $request,
        CampaignRepository $campaignRepo,
        AccountActivitiesRepository $activitiesRepository,
        UserRepository $userRepository
    )
    {
        parent::__construct($request);

        $this->campaignRepository = $campaignRepo;
        $this->activitiesRepository = $activitiesRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param AccountActivitiesRequest $request
     * @return mixed
     * @throws \Throwable
     */
    public function index(AccountActivitiesRequest $request)
    {
        $customers = $this->userRepository->getCustomers();
        $activities = $this->activitiesRepository->filter($request->all());

        return view('activities.index', compact('activities', 'customers'));
    }

    /**
     * @param AccountActivitiesRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function indexAjax(AccountActivitiesRequest $request)
    {
        $res = [
            'error' => 1,
            'content' => ''
        ];

        $activities = $this->activitiesRepository->filter($request->all());

        if(!empty($activities)){
            $res['error'] = 0;
            if($request->get('export') == 'true'){
                $res['link'] = $this->exportToCSV($activities);
            }else{
                $res['content'] = view('activities.table-rows')->with(compact('activities'))->render();
            }
        }

        return response()->json($res);
    }

    /**
     * @param $activities
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    private function exportToCSV($activities){
        $csv_path = public_path('csv');

        if(!is_dir($csv_path)){
            @mkdir($csv_path, 0755);
        }

        $csv_file_name = date('Y-m-d-H-i-s').'-activities.csv';

        $headers = [
            'Action',
            'IP Address',
            'Time'
        ];

        $file_path = $csv_path.'/'.$csv_file_name;

        $fp = fopen($file_path, 'w');
        fputcsv($fp, $headers);

        foreach($activities as $activity){
            fputcsv($fp, [
                'Action' => $activity->action,
                'IP Address' => $activity->ip_address,
                'Time' => $activity->created_at
            ]);
        }

        fclose($fp);

        $save_file_url = url(sprintf('%s%s%s', 'csv', '/', $csv_file_name));

        DownloadFiles::create(['file' => $file_path]);

        return $save_file_url;
    }
}
