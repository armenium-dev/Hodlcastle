<?php

namespace App\Repositories;

use App\Criteria\BelongsToCompanyCriteria;
use App\Models\Group;
use App\Models\Recipient;
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
                foreach ($attributes['recipients_attrs'] as $attrs) {
                    if (self::recipientsMailsValidations($attrs['email'])) {
                        continue;
                    }

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
        $model = $this->find($id);
        try {
            if (isset($attributes['recipients_attrs'])) {
                if (isset($attributes['delete_old']) && $attributes['delete_old'] == 'on') {
                    $model->recipients()->detach();
                    $attributes['recipients_attrs'] = array_filter($attributes['recipients_attrs'], function ($item) {
                        return $item['id'] == null;
                    });
                }

                foreach ($attributes['recipients_attrs'] as $attrs) {
                    if (self::recipientsMailsValidations($attrs['email'])) {
                        continue;
                    }
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

            $model->recipients()->syncWithoutDetaching($ids);

            if (!empty($attributes['removed_recipients_ids'])) {
                $removedRecipientsIds = explode(',', $attributes['removed_recipients_ids']);
                $model->recipients()->detach($removedRecipientsIds);
            }

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
