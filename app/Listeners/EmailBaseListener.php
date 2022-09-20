<?php namespace App\Listeners;

use App\Models\Recipient;
use App\Models\Result;
use App\Repositories\EventRepository;
use App\Repositories\ResultRepository;
use App\Models\Campaign;
use Exception;

class EmailBaseListener
{
    protected $eventRepository;
    protected $resultRepository;

    protected $event;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(EventRepository $eventRepository, ResultRepository $resultRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->resultRepository = $resultRepository;
    }

    public function process()
    {
        $campaign_id = $this->event->sent_email->getHeader('X-Campaign-ID');
        $campaign = Campaign::find($campaign_id);
        if ($campaign)
            return true;
        else
            return false;
            //throw new Exception('No campaign found: error or just testing');
    }

    public function createEvent($type_id)
    {
        $campaign_id = $this->event->sent_email->getHeader('X-Campaign-ID');
        $email = $this->event->sent_email->getHeader('To');

        $eventModel = $this->eventRepository->create([
            'campaign_id' => $campaign_id,
            'email' => $email,
            'time' => $this->event->sent_email->created_at,
            'message' => Result::sTypeTitles()[$type_id],
        ]);

        return $eventModel;
    }

    public function resultCheck($type_id)
    {
        $campaign_id = $this->event->sent_email->getHeader('X-Campaign-ID');
        $email = $this->event->sent_email->getHeader('To');

        $result_check = $this->resultRepository->findWhere([
            'email' => $email,
            'campaign_id' => $campaign_id,
            'type_id' => $type_id,
        ]);

        return $result_check;
    }

    public function createResult($eventModel, $type_id, $geo_info = null)
    {
        $campaign_id = $this->event->sent_email->getHeader('X-Campaign-ID');
        $email = $this->event->sent_email->getHeader('To');
        $recipient = Recipient::where('email', $email)->first();

        $resultModel = $this->resultRepository->create([
            'campaign_id' => $campaign_id,
            'email' => $email,
            'status' => 1,
            'customer_id' => 0,
            'redirect_id' => 0,
            'ip' => $geo_info ? $_SERVER['REMOTE_ADDR'] : '',
            'lat' => $geo_info ? $geo_info->latitude : '',
            'lng' => $geo_info ? $geo_info->longitude : '',
            'reported' => 1,
            'send_date' => $this->event->sent_email->created_at,
            'recipient_id' => $recipient->id,
            'event_id' => $eventModel->id,
            'sent' => 0,
            'click' => 0,
            'open' => 0,
            'attachment' => 0,
            'smish' => 0,
            'type_id' => $type_id,
            'user_agent' => getBrowser(),
        ]);
    }

}