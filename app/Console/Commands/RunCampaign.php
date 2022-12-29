<?php

namespace App\Console\Commands;

use App\Criteria\CampaignsRunningCriteria;
use Illuminate\Console\Command;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Facades\Log;

class RunCampaign extends Command{

    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'campaign:run';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Run mailing for active campaigns';

    private $campaignRepository;

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(CampaignRepository $campaignRepo){
        parent::__construct();
        $this->campaignRepository = $campaignRepo;
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle(){
		Log::stack(['cron'])->debug(__CLASS__);

        $this->campaignRepository->pushCriteria(CampaignsRunningCriteria::class);
        $campaigns = $this->campaignRepository->all();
		Log::stack(['custom'])->debug($campaigns);

        $campaigns->each(function($campaign){
            #$campaign->sendToNextRecipient();
            $campaign->sendToAllRecipientsByCron();
        });

		Log::stack(['cron'])->debug('-------------------------------------------');
    }
}
