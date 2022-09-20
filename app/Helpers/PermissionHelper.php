<?php namespace App\Helpers;

use Auth;

class PermissionHelper
{
    public static function authUserAccessToCompany($company)
    {
        return $company->id == Auth::getUser()->company_id ||
            Auth::user()->can('company.viewAll')
        ;
    }

    public static function authUserViewEmailTemplate($model)
    {
        return $model->is_public || Auth::user()->can('email_template.view_not_public_models') ||
            $model->company_id == Auth::getUser()->company_id
        ;
    }

    public static function authUserEditEmailTemplate($model)
    {
        return Auth::user()->can('email_template.edit_public') ||
            $model->company_id == Auth::getUser()->company_id
        ;
    }

    public static function authUserCopyEmailTemplate($model)
    {
        return $model->is_public ||
            $model->company_id == Auth::getUser()->company_id
            ;
    }

    public static function authUserViewDomain($model)
    {
        return $model->is_public || Auth::user()->can('domain.view_not_public_models') ||
            $model->company_id == Auth::getUser()->company_id
            ;
    }

    public static function authUserEditDomain($model)
    {
        return Auth::user()->can('domain.edit_public') ||
            $model->company_id == Auth::getUser()->company_id
            ;
    }
    
	public static function authUserViewSmsTemplate($model)
	{
		return $model->is_public || Auth::user()->can('sms_template.view_not_public_models') ||
		       $model->company_id == Auth::getUser()->company_id
			;
	}
	
	public static function authUserEditSmsTemplate($model)
	{
		return Auth::user()->can('sms_template.edit_public') ||
		       $model->company_id == Auth::getUser()->company_id
			;
	}
	
	public static function authUserCopySmsTemplate($model)
	{
		return $model->is_public ||
		       $model->company_id == Auth::getUser()->company_id
			;
	}
	
}