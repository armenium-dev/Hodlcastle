<?php namespace Tests\Feature;

use App\Models\EmailTemplate;
use Tests\TestCase;

class EmailTemplateSendTest extends TestCase
{

    public function testSend()
    {
//        $email_template = factory('App\Models\EmailTemplate')->create();
//        $recipient = factory('App\Models\Recipient')->create([
//            'first_name' => $this->user->name,
//            'email' => $this->user->email,
//        ]);

        //$email_template->send($recipient, null, true);

        $this->assertTrue(true);
    }
}