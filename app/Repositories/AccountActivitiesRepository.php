<?php

namespace App\Repositories;

use App\Models\AccountActivity;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class AccountActivitiesRepository.
 *
 * @package namespace App\Repositories;
 */
class AccountActivitiesRepository extends ParentRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return AccountActivity::class;
    }

    /**
     * filter, fetch data
     * @param $params
     * @return mixed
     */
    public function filter($params)
    {
        $query = $this->select('account_activities.id', 'user_id', 'action', 'ip_address', 'account_activities.created_at', 'users.name');
        $query->join('users', 'users.id', '=', 'account_activities.user_id');
        $customerId = null;

        if (!empty($params['form_data'])) {
            $form_data = [];
            parse_str($params['form_data'], $form_data);

            if (!empty($form_data['start_date']) && empty($form_data['end_date'])) {
                $start_date = Carbon::parse(str_replace('/', '.', $form_data['start_date']))->format('Y-m-d');
                $query->where('created_at', '>=', $start_date);
            } elseif (empty($form_data['start_date']) && !empty($form_data['end_date'])) {
                $end_date = Carbon::parse(str_replace('/', '.', $form_data['end_date']))->format('Y-m-d');
                $query->where('created_at', '<=', $end_date);
            } elseif (!empty($form_data['start_date']) && !empty($form_data['end_date'])) {
                $start_date = Carbon::parse(str_replace('/', '.', $form_data['start_date']))->format('Y-m-d');
                $end_date = Carbon::parse(str_replace('/', '.', $form_data['end_date']))->format('Y-m-d');
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }

            if (!empty($form_data['action'])) {
                $query->where('action', 'like', $form_data['action'] . "%");
            }

            if ($params['dir'] != 'reset') {
                $query->orderBy($params['name'], $params['dir']);
            }
        }

//        if (!Auth::user()->hasRole('captain')) {
//            $customerId = Auth::id();
//        } else {
//            if (!empty($form_data['customer'])) {
//                $customerId = $form_data['customer'];
//            }
//        }

        if (!empty($customerId)) {
            $query->where('user_id', $customerId);
        }

//dd($query->get());
        return $query->get();
    }
}
