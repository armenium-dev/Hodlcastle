<?php

use App\Models\ShortLink;
use GeoIp2\Database\Reader;
use Illuminate\Support\Facades\Log;

const TEST_GROUP_NAME = 'Test group Art';
const RECIPIENT_NAME = 'Art test recipient';
const RECIPIENT_EMAIL = 'arybachu@verifysecurity.nl';

function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-@.]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

function getBrowser() {
    $out = 'other';

    if (\Browser::isFirefox()) {
        $out = 'firefox';
    } elseif (\Browser::isChrome()) {
        $out = 'chrome';
    } elseif (\Browser::isOpera()) {
        $out = 'opera';
    } elseif (\Browser::isSafari()) {
        $out = 'safari';
    } elseif (\Browser::isIE()) {
        $out = 'ie';
    }

    return $out;
}

function hash2IntsTime($int1, $int2) {
    return substr(md5($int1 . $int2 . time()), 0, 15);
}

function geoInfo($service = 'maxmind_database') {
    $ip = $_SERVER['REMOTE_ADDR'];
    $data = null;

    if($ip == '127.0.0.1'){
    	return $data;
    }

    if ($service == 'ipapi') {
        $resp = file_get_contents('https://ipapi.co/' . $ip . '/json/');
        $data = json_decode($resp);
    }

    if ($service == 'maxmind_database') {
        $reader = new Reader(storage_path('app/geoip.mmdb'));
        $record = $reader->city($_SERVER['REMOTE_ADDR']);
        if ($record->location)
            $data = $record->location;
    }

    return $data;
}

function getDomainFromEmail($email) {
    $url = null;

    $parts = explode('@', $email);
    if (count($parts) == 2)
        $url = $parts[1];

    return $url;
}

/*function generateShortUrl($src_url, $logging = false){

	if($logging)
		Log::stack(['custom'])->debug($src_url);

	$url = parse_url($src_url);
	$code = ShortLink::generate($src_url);
	$dst_url = $url['scheme'] . '://' . env('SHORT_URL_DOMAIN') . '/short/' . $code->code;

	if($logging)
		Log::stack(['custom'])->debug($dst_url);

	return $dst_url;
}*/
