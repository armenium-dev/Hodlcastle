<?php
namespace App\Http\Middleware;

use App\Helpers\PermissionHelper;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class GenerateMenus{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next){
		if(Auth::check()){
			\Menu::make('MyNavBar', function($menu){
				$menu->add('Dashboard', ['route' => 'home'])->data('slug', 'dashboard')->data('icon', 'fa-dashboard');
				$menu->add('Recipient Groups', ['route' => 'groups.index'])->data('permission', 'groups.index')->data('slug', 'groups')->data('icon', 'fa-users');
				$menu->add('Email Templates', ['route' => 'emailTemplates.index'])->data('permission', 'emailTemplates.index')->data('slug', 'emailTemplates')->data('icon', 'fa-envelope');
				$menu->add('Source Domains', ['route' => 'domains.index'])->data('permission', 'domains.index')->data('slug', 'domains')->data('icon', 'fa-map-signs');
				$menu->add('Campaigns', ['route' => 'campaigns.index'])->data('permission', 'campaigns.index')->data('slug', 'campaigns')->data('icon', 'fa-calendar');
				$menu->add('Scenarios', ['route' => 'scenarios.index'])->data('permission', 'scenarios.index')->data('slug', 'scenarios')->data('icon', 'fa-play');
				$menu->add('Documentation', ['route' => 'documentation.index'])->data('permission', 'documentation.index')->data('slug', 'documentation')->data('icon', 'fa-book');
                $menu->add('Trainings', ['route' => 'trainings.index'])->data('permission', 'trainings.index')->data('slug', 'trainings')->data('icon', 'fa-graduation-cap');
                #$menu->add('Training Statistics', ['route' => 'trainingStatistics.index'])->data('permission', 'trainings.index')->data('slug', 'trainingStatistics')->data('icon', 'fa-graduation-cap');
                #$menu->add('Training Notify Templates', ['route' => 'trainingNotifyTemplates.index'])->data('permission', 'trainings.index')->data('slug', 'trainingNotifyTemplates')->data('icon', 'fa-graduation-cap');
				$menu->add('Smishing', ['route' => 'smishing'])->data('permission', 'campaigns.index')->data('slug', 'smishing')->data('icon', 'fa-phone');
                $menu->add('Landing Templates', ['route' => 'landingTemplates.index'])->data('permission', 'emailTemplates.index')->data('slug', 'landingTemplates')->data('icon', 'fa-code');

                $menu->divide();

				$menu->add('Email Scenario Builder', ['route' => 'scenario.builder'])->data('permission', 'scenario.builder')->data('slug', 'scenario')->data('icon', 'fa-recycle');
				$menu->add('Companies', ['route' => 'companies.index'])->data('permission', 'companies.index')->data('slug', 'companies')->data('icon', 'fa-anchor');
				$menu->add('Users', ['route' => 'users.index'])->data('permission', 'users.index')->data('slug', 'users')->data('icon', 'fa-anchor');
				$menu->add('Roles', ['route' => 'roles.index'])->data('permission', 'roles.index')->data('slug', 'roles')->data('icon', 'fa-anchor');
				$menu->add('Permissions', ['route' => 'permissions.index'])->data('permission', 'permissions.index')->data('slug', 'permissions')->data('icon', 'fa-anchor');
				$menu->add('All Sent Emails', ['route' => 'mailTracker_Index'])->data('permission', 'mailTracker_Index')->data('slug', 'mailTracker_Index')->data('icon', 'fa-anchor');
				$menu->add('Events', ['route' => 'events.index'])->data('permission', 'events.index')->data('slug', 'events')->data('icon', 'fa-anchor');
				$menu->add('Results', ['route' => 'results.index'])->data('permission', 'results.index')->data('slug', 'results')->data('icon', 'fa-anchor');
				$menu->add('Supergroups', ['route' => 'supergroups.index'])->data('permission', 'supergroups.index')->data('slug', 'supergroups')->data('icon', 'fa-anchor');
				$menu->add('Landings', ['route' => 'landings.index'])->data('permission', 'landings.index')->data('slug', 'landings')->data('icon', 'fa-plane');
                $menu->add('Modules', ['route'  => 'modules.index'])->data('permission', 'modules.index')->data('slug', 'modules')->data('icon', 'fa-graduation-cap');
                $menu->add('Courses', ['route'  => 'courses.index'])->data('permission', 'courses.index')->data('slug', 'courses')->data('icon', 'fa-graduation-cap');
                $menu->add('Languages', ['route'  => 'languages.index'])->data('permission', 'modules.index')->data('slug', 'languages')->data('icon', 'fa-language');
				$menu->add('SMS Templates', ['route' => 'smsTemplates.index'])->data('permission', 'smsTemplates.index')->data('slug', 'smsTemplates')->data('icon', 'fa-phone');
            })->filter(function($item){
				if(Auth::user()->can($item->data('permission'))){
					#dump($item->data('slug'));
					return Auth::user()->hasRole('captain') ? true : PermissionHelper::companyAccessToSection($item->data('slug'));
				}

				return false;
			});
		}

		return $next($request);
	}
}
