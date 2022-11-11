<?php

return [
	'active_account' => 0,

	'accounts' => [
		0 => [
			'account_sid' => env('TWILIO_ACCOUNT_SID'),
			'auth_token' => env('TWILIO_AUTH_TOKEN'),
			'sms_from' => env('TWILIO_SMS_FROM'),
		],
		1 => [
			'account_sid' => env('TWILIO_ACCOUNT_SID_1'),
			'auth_token' => env('TWILIO_AUTH_TOKEN_1'),
			'sms_from' => env('TWILIO_SMS_FROM_1'),
		],
	]
];
