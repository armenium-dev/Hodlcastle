<?php

namespace App\Repositories;

use App\Models\Training;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use InfyOm\Generator\Common\BaseRepository;
use Auth;
//use JDT\LaravelEmailTemplates\TemplateMailable;
//use Mail;
use App\Mail\TrainingSending;
use Illuminate\Support\Facades\Mail;

/**
 * Class TrainingRepository
 * @package App\Repositories
 *
 * @method Training findWithoutFail($id, $columns = ['*'])
 * @method Training find($id, $columns = ['*'])
 * @method Training first($columns = ['*'])
*/
class TrainingRepository extends ParentRepository
{
    public static $recipient_code = '';

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'module_id',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Training::class;
    }

    public function create(array $input)
    {
        $input['user_id'] = Auth::user()->id;

        $model = parent::create($input);

        $this->sendToAllRecipients($model);

        return $model;
    }

    public function sendToAllRecipients($model)
    {
        foreach ($model->groups as $group) {
            $recipients = $group->recipients()->get();

            foreach ($recipients as $recipient) {
                $recipient->attachToTraining($model);
                $this->setRicipentCode($recipient, $model->id);
                $this->send($recipient, $model);
                $recipient->setIsSentToTraining($model);
            }
        }
    }

    public function setRicipentCode($recipient, $id)
    {
        $trainingWithPivot = $recipient->trainings()->find($id);
        if ($trainingWithPivot) {
            self::$recipient_code = $trainingWithPivot->pivot->code;
        }
    }

    public function send($recipient, $training)
    {
        $objDemo = new \stdClass();

        $objDemo->first_name = $recipient->first_name;
        $objDemo->last_name = $recipient->last_name;
        $objDemo->url = $this->makeTrainingUrl($recipient, $training);

        Mail::to($recipient->email)->send(new TrainingSending($objDemo));
    }

    public function makeTrainingUrl($recipient, $training)
    {
        if (!$training) {
            return false;
        }

        $trainingWithPivot = $recipient->trainings()->find($training->id);
//        $href = '<mytag mylink="{main_domain}/' . $trainingWithPivot->pivot->code . '">Training link</mytag>';
        $href = env('APP_URL') . '/tng/' . $trainingWithPivot->pivot->code;

        return $href;
    }
}