<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Facades\Log;

class SoftLimitCheck extends Command{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'company:softlimit';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check and notify captains if any company exceeded their soft limit';

	private $companyRepository;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(CompanyRepository $companyRepository){
		parent::__construct();
		$this->companyRepository = $companyRepository;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		Log::stack(['cron'])->debug(__CLASS__);

		$companies = $this->companyRepository->all();

		$companies->each(function($company){
			$company->checkSoftLimit();
		});

		Log::stack(['cron'])->debug('-------------------------------------------');
	}
}
