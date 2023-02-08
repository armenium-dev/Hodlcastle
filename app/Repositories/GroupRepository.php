<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\Group;
use App\Models\Recipient;
use InfyOm\Generator\Common\BaseRepository;
use Exception;
use Flash;
use Auth;

/**
 * Class GroupRepository
 * @package App\Repositories
 * @version June 23, 2018, 6:42 pm UTC
 *
 * @method Group findWithoutFail($id, $columns = ['*'])
 * @method Group find($id, $columns = ['*'])
 * @method Group first($columns = ['*'])
 */
class GroupRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'company_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Group::class;
    }

    public function create(array $attributes)
    {
        $ids = [];

        try {
            if (isset($attributes['recipients_attrs'])) {
                foreach ($attributes['recipients_attrs'] as $recipient) {
                    if (self::recipientsMailsValidations($recipient['email'])) {
                        continue;
                    }

                    $recipientModel = Recipient::create($recipient);
                    $ids[] = $recipientModel->id;
                }
            }

            $attributes['recipients'] = $ids;

            $model = parent::create($attributes);
            $model->recipients()->sync($ids);

            return $model;
        } catch (Exception $e) {
            Flash::error($e->getMessage());

            //return response()->json(['result' => 0, 'msg' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()], 400);
        }
    }

    public function update(array $attributes, $id)
    {
        $ids = [];

//        // get all recipients in group
//        $recipients_ids = [];
//        foreach ($this->findWithoutFail($id)->recipients as $rec){
//            $recipients_ids[] = $rec->id;
//        }

        try {
            if (isset($attributes['recipients_attrs'])) {
                foreach ($attributes['recipients_attrs'] as $attrs) {

                    if (self::recipientsMailsValidations($attrs['email'])) {
                        continue;
                    }

                    $attrs['group_id'] = $id;
                    $recipient = Recipient::where('email', $attrs['email'])->first();
                    if ($recipient) {
                        $recipient->fill($attrs);
                        $recipient->save();
                    } else {
                        $recipient = Recipient::create($attrs);
                    }
                    $ids[] = $recipient->id;
                }
            }

            $attributes['recipients'] = $ids;

            $model = $this->find($id);

            if (isset($attributes['delete_old']) && $attributes['delete_old'] == 'on') {
                $model->recipients()->sync($ids);
            } else {
                $model->recipients()->syncWithoutDetaching($ids);
            }

//            // delete recipients in Recipient model
//            $result_ids_diff = array_diff($recipients_ids, $ids);
//            if (!empty($result_ids_diff)) {
//                foreach ($result_ids_diff as $item) {
//                    $rec = Recipient::find($item);
//                    $rec->delete();
//                }
//            }

            return $model;
        } catch (Exception $e) {
            Flash::error($e->getMessage());

            return null;
        }
    }

//    public function checkCapacity($group, array $recipients_attrs = [])
//    {
//        if (Auth::user()->company->max_recipients > 0) {
//            $emails = array_pluck($attributes['recipients_attrs'], 'email');
//
//            $count = $group->recipients()->whereIn('email', $emails)->count();
//
//            $recipients_capacity = Auth::user()->company->recipientsCapacity - (count($attributes['recipients_attrs']) - $count);
//
//            return $recipients_capacity >= 0;
//        } else {
//            return true;
//        }
//
//    }

    public function listForCompany()
    {
        $models = $this->pushCriteria(new BelongsToCompanyCriteria())->pluck('name', 'id');

        return $models;
    }

    public function recipientsMailsValidations($email)
    {
        $regexp = '/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/';
        return preg_match($regexp, $email) ? false : true;
    }
}
