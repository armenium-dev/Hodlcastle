<?php

namespace App\Console\Commands;

use App\Criteria\CompaniesRunningCriteria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Repositories\CompanyRepository;
use Carbon\Carbon;

class RunCompany extends Command{
	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'company:run';
	
	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Run for checking company expiration date';
	
	private $companyRepository;
	
	/**
	 * Create a new command instance.
	 * @return void
	 */
	public function __construct(CompanyRepository $companyRepo){
		parent::__construct();
		$this->companyRepository = $companyRepo;
	}
	
	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		Log::stack(['cron'])->debug(__CLASS__);
		
		$this->companyRepository->pushCriteria(CompaniesRunningCriteria::class);
		$companies = $this->companyRepository->all();
		$companies_count = count($companies);

		Log::stack(['cron'])->debug('Companies Count = '.$companies_count);

		$companies->each(function($company){
			Log::stack(['cron'])->debug('Updatet Company ID = '.$company['id'].', '.$company['expires_at']);
			$this->companyRepository->deactivate($company['id']);
		});
		
		Log::stack(['cron'])->debug('-------------------------------------------');
	}
}
