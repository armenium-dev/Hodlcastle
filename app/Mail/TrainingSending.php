<?php

namespace App\Mail;

use App\Models\Recipient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class TrainingSending extends Mailable{
	use Queueable, SerializesModels;

	/**
	 * The demo object instance.
	 *
	 * @var Demo
	 */
	public $data;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($data){
		$this->data = $data;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build(){
		if(empty($this->data->template->content)){
			$this
				->subject($this->data->subject)
				->from(config('mail.email_noreply'))
				->markdown($this->data->view);
		}else{
			$this
				->subject($this->data->template->subject)
				->from(config('mail.email_noreply'))
				->html($this->buildMailContent());
		}
		$this->withSwiftMessage(function($message){
			$message->getHeaders()->addTextHeader('X-No-Track', Str::random(10));
		});

		return $this;
	}

	public function buildMailContent(){
		$content = $this->data->template->content;

		$keywords = [
			'{{.FirstName}}' => $this->data->recipient->first_name,
			'{{.LastName}}' => $this->data->recipient->last_name,
			'{{.Email}}' => $this->data->recipient->email,
			'{{.Position}}' => $this->data->recipient->position,
			'{{.Department}}' => $this->data->recipient->department,
			'{{.YEAR}}' => date('Y'),
			'{{.MONTH}}' => date('m'),
			'{{.DAY}}' => date('d'),
			'{{.URL}}' => $this->data->url,
		];

		foreach($keywords as $k => $v){
			$content = str_replace($k, $v, $content);
		}

		return $content;
	}

}
