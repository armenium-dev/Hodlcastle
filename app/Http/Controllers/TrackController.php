<?php namespace App\Http\Controllers;

use App\Events\EmailReportedEvent;
use App\Models\Campaign;
use App\Models\Landing;
use App\Repositories\ResultRepository;
use Illuminate\Http\Request;
use Event;
use Exception;
use jdavidbakr\MailTracker\Model\SentEmail;

class TrackController extends Controller
{

    private $resultRepository;

    public function __construct(ResultRepository $resultRepo)
    {
        $this->resultRepository = $resultRepo;
    }
    
    public function index($code, Request $request)
    {
        $campaign = Campaign::whereHas('recipients', function ($q) use ($code) {
            $q->where('code', $code);
        })->first();

        if (!$campaign->schedule)
            return;

        if ($campaign->schedule->send_to_landing) {
            if ($campaign->schedule->landing || $campaign->schedule->landing->name == 'default') {
                $landing = $campaign->schedule->landing;
            } else {
                $landing = Landing::whereName('default')->first();
            }

            $html = $landing->content_raw;
            $styles = $landing->styles;

            if ($html)
                return view('track.landing', compact('html', 'styles'));
        } else {
            return \Redirect::to($campaign->schedule->redirect_url);
        }
    }

    public function tracktest($id_or_redirect_url)
    {
        if (is_int($id_or_redirect_url)) {
            $landing = Landing::find($id_or_redirect_url);
            if (!$landing)
                return;

            $html = $landing->content_raw;

            if ($html)
                return view('track.landing', ['html' => $html, 'styles' => $landing->styles]);
        } else {
            return \Redirect::to($id_or_redirect_url);
        }
    }

    public function getReport($hash, $campaign_id, $pmid)
    {
        if (!$hash || !$campaign_id || !$pmid) {
            throw new Exception('3 parms required');
        }

        $tracker = SentEmail::where('hash', $hash)->first();
        if ($tracker) {
            $_campaign_id = $tracker->getHeader('X-Campaign-ID');
            $_pmid = $tracker->getHeader('X-PMID');
            $_mailer_hash = $tracker->getHeader('X-Mailer-Hash');
            //dd($campaign_id, $pmid, $mailer_hash);
            if ($_campaign_id != $campaign_id ||
                $_pmid != $pmid ||
                $_mailer_hash != $hash) {
                throw new Exception('Headers are not valid. campaign id: ' . $campaign_id . ' pnid: ' . $pmid . ' hash: ' . $hash);
            }
            Event::fire(new EmailReportedEvent($tracker));
        } else {
            throw new Exception('Hash is not valid');
        }

        return 1;
    }

    public function postReport()
    {
        $tracker = SentEmail::where('hash', \Request::get('hash'))->first();
        if ($tracker) {
            $campaign_id = $tracker->getHeader('X-Campaign-ID');
            $pmid = $tracker->getHeader('X-PMID');
            $mailer_hash = $tracker->getHeader('X-Mailer-Hash');

            if (!$campaign_id || !$pmid || !$mailer_hash) {
                throw new Exception('Headers are not valid ' . $campaign_id . ' ' . $pmid . ' ' . $mailer_hash);
            }
            Event::fire(new EmailReportedEvent($tracker));
        } else {
            throw new Exception('Hash is not valid');
        }

        return 1;
    }

    public function saveFakeAuthCount($params) {

        $data = explode(',', base64_decode($params));

        $fake_auth = $this->resultRepository->findFakeAuthByEmailAndCompaign($data[1], $data[0]);

        if ($fake_auth) {
            return response()->json([
                'status' => 'Error',
            ]);
        }

        $create = $this->resultRepository->create([
            'campaign_id' => $data[1],
            'email' => $data[0],
            'status' => 1,
            'customer_id' => 0,
            'redirect_id' => 0,
            'ip' => '',
            'lat' => '',
            'lng' => '',
            'reported' => 1,
            'send_date' => date('Y-m-d H:i:s'),
            'recipient_id' => 0,
            'event_id' => 0,
            'sent' => 0,
            'click' => 0,
            'open' => 0,
            'attachment' => 0,
            'fake_auth' => 1,
            'smish' => 0,
            'type_id' => 7,
            'user_agent' => getBrowser(),
        ]);

        return response()->json([
            'id' => $create,
            'status' => 'Success',
        ]);
    }
}
