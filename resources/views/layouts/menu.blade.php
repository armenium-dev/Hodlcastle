<li class="{{ Request::is('/') ? 'active' : '' }}">
    <a href="{!! route('home') !!}"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
</li>
@if(Auth::user()->can('groups.index'))
<li class="{{ Request::is('groups*') ? 'active' : '' }}">
    <a href="{!! route('groups.index') !!}"><i class="fa fa-users"></i><span>Recipient Groups</span></a>
</li>
@endif
@if(Auth::user()->can('groups.index'))
<li class="{{ Request::is('emailTemplates*') ? 'active' : '' }}">
    <a href="{!! route('emailTemplates.index') !!}"><i class="fa fa-envelope"></i><span>Email Templates</span></a>
</li>
@endif
</li>
<li class="{{ Request::is('domains*') ? 'active' : '' }}">
    <a href="{!! route('domains.index') !!}"><i class="fa fa-map-signs"></i><span>Source Domains</span></a>
</li>
<li class="{{ Request::is('landings*') ? 'active' : '' }}">
    <a href="{!! route('landings.index') !!}"><i class="fa fa-plane"></i><span>Landings</span></a>
</li>
<li class="{{ Request::is('campaigns*') ? 'active' : '' }}">
    <a href="{!! route('campaigns.index') !!}"><i class="fa fa-calendar"></i><span>Campaigns</span></a>
</li>
<hr>
<li class="{{ Request::is('documentation*') ? 'active' : '' }}">
    <a href="{!! route('documentation.index') !!}"><i class="fa fa-book"></i><span>Documentation</span></a>
</li>
<li class="{{ Request::is('documentation*') ? 'active' : '' }}">
    <a href="{!! route('documentation.index') !!}"><i class="fa fa-graduation-cap"></i><span>Security Awareness Training</span></a>
</li>
<li class="{{ Request::is('scenarios/builder*') ? 'active' : '' }}">
    <a href="{!! route('scenarios.builder.index') !!}"><i class="fa fa-anchor"></i><span>Scenario Builder</span></a>
</li>
<li class="{{ Request::is('companies*') ? 'active' : '' }}">
    <a href="{!! route('companies.index') !!}"><i class="fa fa-anchor"></i><span>Companies</span></a>
</li>
<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('users.index') !!}"><i class="fa fa-anchor"></i><span>Users</span></a>
</li>
<li class="{{ Request::is('roles*') ? 'active' : '' }}">
    <a href="{!! route('roles.index') !!}"><i class="fa fa-anchor"></i><span>Roles</span></a>
</li>
<li class="{{ Request::is('permissions*') ? 'active' : '' }}">
    <a href="{!! route('permissions.index') !!}"><i class="fa fa-anchor"></i><span>Permissions</span></a>
</li>
<li class="{{ Request::is('mailTracker') ? 'active' : '' }}">
    <a href="{{route('mailTracker_Index',['page'=>session('mail-tracker-index-page')])}}"><i class="fa fa-anchor"></i><span>All Sent Emails</span></a>
</li>
<li class="{{ Request::is('events*') ? 'active' : '' }}">
    <a href="{!! route('events.index') !!}"><i class="fa fa-anchor"></i><span>Events</span></a>
</li>
<li class="{{ Request::is('results*') ? 'active' : '' }}">
    <a href="{!! route('results.index') !!}"><i class="fa fa-anchor"></i><span>Results</span></a>
</li>

<li class="{{ Request::is('supergroups*') ? 'active' : '' }}">
    <a href="{!! route('supergroups.index') !!}"><i class="fa fa-edit"></i><span>Supergroups</span></a>
</li>

<li class="{{ Request::is('schedules*') ? 'active' : '' }}">
    <a href="{!! route('schedules.index') !!}"><i class="fa fa-edit"></i><span>Schedules</span></a>
</li>

