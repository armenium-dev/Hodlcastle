<?php namespace App\Http\Middleware;

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
				$menu->add('Dashboard', ['route' => 'home'])->data('icon', 'fa-dashboard');
				$menu->add('Recipient Groups', ['route' => 'groups.index'])->data('permission', 'groups.index')->data('icon', 'fa-users');
				$menu->add('Email Templates', ['route' => 'emailTemplates.index'])->data('permission', 'emailTemplates.index')->data('icon', 'fa-envelope');
				$menu->add('Source Domains', ['route' => 'domains.index'])->data('permission', 'domains.index')->data('icon', 'fa-map-signs');
				$menu->add('Campaigns', ['route' => 'campaigns.index'])->data('permission', 'campaigns.index')->data('icon', 'fa-calendar');
				$menu->add('Scenarios', ['route' => 'scenarios.index'])->data('permission', 'scenarios.index')->data('icon', 'fa-play');
				$menu->add('Documentation', ['route' => 'documentation.index'])->data('permission', 'documentation.index')->data('icon', 'fa-book');
                $menu->add('Trainings', ['route' => 'trainings.index'])->data('permission', 'trainings.index')->data('icon', 'fa-graduation-cap');
                $menu->add('Training Statistics', ['route' => 'trainingStatistics.index'])->data('permission', 'trainings.index')->data('icon', 'fa-graduation-cap');
				$menu->add('Smishing', ['route' => 'campaigns.smishing'])->data('permission', 'campaigns.index')->data('icon', 'fa-calendar');

				$menu->divide();

				$menu->add('Email Scenario Builder', ['route' => 'scenario.builder'])->data('permission', 'scenario.builder')->data('icon', 'fa-recycle');
				$menu->add('Companies', ['route' => 'companies.index'])->data('permission', 'companies.index')->data('icon', 'fa-anchor');
				$menu->add('Users', ['route' => 'users.index'])->data('permission', 'users.index')->data('icon', 'fa-anchor');
				$menu->add('Roles', ['route' => 'roles.index'])->data('permission', 'roles.index')->data('icon', 'fa-anchor');
				$menu->add('Permissions', ['route' => 'permissions.index'])->data('permission', 'permissions.index')->data('icon', 'fa-anchor');
				$menu->add('All Sent Emails', ['route' => 'mailTracker_Index'])->data('permission', 'mailTracker_Index')->data('icon', 'fa-anchor');
				$menu->add('Events', ['route' => 'events.index'])->data('permission', 'events.index')->data('icon', 'fa-anchor');
				$menu->add('Results', ['route' => 'results.index'])->data('permission', 'results.index')->data('icon', 'fa-anchor');
				$menu->add('Supergroups', ['route' => 'supergroups.index'])->data('permission', 'supergroups.index')->data('icon', 'fa-anchor');
				$menu->add('Landings', ['route' => 'landings.index'])->data('permission', 'landings.index')->data('icon', 'fa-plane');
                $menu->add('Modules', ['route'  => 'modules.index'])->data('permission', 'modules.index')->data('icon', 'fa-graduation-cap');
                $menu->add('Courses', ['route'  => 'courses.index'])->data('permission', 'courses.index')->data('icon', 'fa-graduation-cap');
				$menu->add('SMS Templates', ['route' => 'smsTemplates.index'])->data('permission', 'smsTemplates.index')->data('icon', 'fa-phone');
            })->filter(function($item){
					if(Auth::user()->can($item->data('permission'))){
						return true;
					}

					return false;
				});
		}

		return $next($request);
	}
}
