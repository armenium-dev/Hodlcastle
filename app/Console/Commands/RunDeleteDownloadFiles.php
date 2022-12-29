<?php

namespace App\Console\Commands;

use App\Mail\TrainingSending;
use App\Models\DownloadFiles;
use App\Models\Recipient;
use App\Models\Training;
use App\Models\TrainingStatistic;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Repositories\CompanyRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RunDeleteDownloadFiles extends Command{

	/**
	 * The name and signature of the console command.
	 * @var string
	 */
	protected $signature = 'delete_download_files:run';
	
	/**
	 * The console command description.
	 * @var string
	 */
	protected $description = 'Run for deletion download files';
	

	/**
	 * Create a new command instance.
	 * @return void
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		Log::stack(['cron'])->debug(__CLASS__);

		$DOWNLOAD_FILE_LIFE_TIME = env('DOWNLOAD_FILE_LIFE_TIME', 5);

		$delete_time = Carbon::now()->subMinutes($DOWNLOAD_FILE_LIFE_TIME);

		$files = DownloadFiles::where('created_at', '<=', $delete_time)->get();

		#Log::stack(['cron'])->debug($delete_time);
		#Log::stack(['cron'])->debug($files->count());

		if($files->count()){
			$this->deleteFiles($files);
		}

		Log::stack(['cron'])->debug('-------------------------------------------');
	}

	private function deleteFiles($download_files){
		foreach($download_files as $file_entry){
			if(unlink($file_entry->file)){
				$file_entry->delete();
			}
		}
	}
}
