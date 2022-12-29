<?php

namespace App\Http\Controllers;

use App\Criteria\BelongsToCompanyCriteria;
use App\Http\Requests\CreateGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Repositories\GroupRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Auth;
use Flash;
use PHPExcel_IOFactory;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\CompanyRepository;
use Exception;
use App\Imports\RecipientsImport;
use Maatwebsite\Excel\Facades\Excel;
#use Maatwebsite\Excel\Excel;
#use App\Http\Controllers\Controller;
#use Excel;

class GroupController extends AppBaseController
{
    /** @var  GroupRepository */
    private $groupRepository;
    private $companyRepository;

    public function __construct(Request $request, GroupRepository $groupRepo, CompanyRepository $companyRepo)
    {
        parent::__construct($request);

        $this->groupRepository = $groupRepo;
        $this->companyRepository = $companyRepo;
    }

    /**
     * Display a listing of the Group.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->groupRepository->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(BelongsToCompanyCriteria::class);

        $groups = $this->groupRepository->all();

        return view('groups.index')
            ->with('groups', $groups);
    }

    /**
     * Show the form for creating a new Group.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $this->companyRepository->pushCriteria(new RequestCriteria($request));
        $companies = $this->companyRepository->pluck('name', 'id');

        return view('groups.create')->with('companies', $companies);
    }

    /**
     * Store a newly created Group in storage.
     *
     * @param CreateGroupRequest $request
     *
     * @return Response
     */
    public function store(CreateGroupRequest $request)
    {
        $input = $request->all();

        if (!isset($input['recipients_attrs']) ||
            Auth::user()->company->checkCapacity($input['recipients_attrs'])
        ) {
            if (Auth::user()->hasRole('customer')) {
                $input['company_id'] = Auth::user()->company_id;
            }
            $group = $this->groupRepository->create($input);

            Flash::success('Group saved successfully.');
        } else {
            Flash::error('Recipients capacity exceeded');
            return redirect(route('groups.create'));
        }

        return redirect(route('groups.index'));
    }

    /**
     * Display the specified Group.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        $this->groupRepository->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(BelongsToCompanyCriteria::class);

        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('groups.index'));
        }

        return view('groups.show')->with('group', $group);
    }

    /**
     * Show the form for editing the specified Group.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $this->groupRepository->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(BelongsToCompanyCriteria::class);

        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('groups.index'));
        }
        $this->companyRepository->pushCriteria(new RequestCriteria($request));
        $companies = $this->companyRepository->pluck('name', 'id');

        return view('groups.edit')->with('group', $group)->with('companies', $companies);
    }

    /**
     * Update the specified Group in storage.
     *
     * @param  int              $id
     * @param UpdateGroupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGroupRequest $request)
    {
        $this->groupRepository->pushCriteria(new RequestCriteria($request))
            ->pushCriteria(BelongsToCompanyCriteria::class);

        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('groups.index'));
        }

        $input = $request->all();
        $comp_id = $group->company_id;

        if (isset($input['recipients_attrs'])) {

            if ($group->company->checkCapacity($input['recipients_attrs'], $group)) {

                foreach ($input['recipients_attrs'] as $key => $rec) {
                    $domain = explode('@', $rec['email'])[1];
                    if (!$this->companyRepository->checkDomain($comp_id, $domain)) {
                        unset($input['recipients_attrs'][$key]);
                    }
                }

                $this->groupRepository->update($input, $id);

                Flash::success('Group updated successfully.');
            } else {
                Flash::error('Recipients capacity exceeded');
            }

        } else {
            Flash::error('No recipients in the group');
        }

        return redirect(route('groups.edit', ['group' => $id]));
    }

    /**
     * Remove the specified Group from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $group = $this->groupRepository->findWithoutFail($id);

        if (empty($group)) {
            Flash::error('Group not found');

            return redirect(route('groups.index'));
        }

        $this->groupRepository->delete($id);

        Flash::success('Group deleted successfully.');

        return redirect(route('groups.index'));
    }

    public function import_old(Request $request)
    {
        if (!$request->ajax())
            return 'Not allowed';

        $recipients = [];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = \Excel::load($path, function($reader) {
            })->get();

            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $recipients[] = array_map('clean', $value->toArray());
                }
            }
        }

        return response()->json($recipients);
    }

    public function import(Request $request)
    {
        if (!$request->ajax())
            return 'Not allowed';

        $recipients = [];
	    
        if($request->hasFile('file')){
        	$originalName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->getRealPath();
            $fileExt = $this->get_file_ext($originalName);
            
            switch($fileExt){
	            case "csv":
		            $handle = fopen($path, "r");
		            $k = 1;
		            $cols = [];
		            while(($data = fgetcsv($handle, 0, ",")) !== false){
			            if($k == 1){
				            $cols = $data;
			            }else{
				            foreach($data as $c => $n){
					            $recipients[$k][$cols[$c]] = $n;
				            }
			            }
			            $k++;
		            }
		            fclose($handle);
	            	break;
	            case "xls":
	            case "xlsx":
	                $inputFileType = 'Excel2007';
		            $objReader   = PHPExcel_IOFactory::createReader($inputFileType);
		            $objReader->setReadDataOnly(true);
		            $objPHPExcel = $objReader->load($path);
		            $objPHPExcel->setActiveSheetIndex(0);
		            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, false, false, true);
	
		            if(!empty($sheetData)){
			            $cols = [];
			            foreach($sheetData as $k => $v){
				            if($k == 1){
					            $cols = $v;
				            }else{
					            foreach($v as $c => $n){
						            $recipients[$k][$cols[$c]] = $n;
					            }
				            }
			            }
		            }
	            	break;
            }
	        
	        #dd($recipients);
	
        }

        return response()->json($recipients);
    }

    public function vue(Request $request)
    {
        $group = $this->groupRepository->findWithoutFail($request->get('id'));
        $group->recipients;

        return response()->json(compact('group'));
    }
	
	public function get_file_ext($file_path){
		$base_name = basename($file_path);
		$a         = explode('.', $base_name);
		$ext       = end($a);
		
		return strtolower($ext);
	}
	
}
