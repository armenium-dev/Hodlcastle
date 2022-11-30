<ul class="nav nav-tabs">
	<li class="{{ $active == 'trainings' ? 'active' : '' }}"><a href="{!! route('trainings.index') !!}"><i class="fa fa-graduation-cap"></i> Trainings</a></li>
    <li class="{{ $active == 'training_statistics' ? 'active' : '' }}"><a href="{!! route('trainingStatistics.index') !!}"><i class="fa fa-dashboard"></i> Training Statistics</a></li>
    <li class="{{ $active == 'training_statistics_export' ? 'active' : '' }}"><a href="{!! route('trainingStatistic.export') !!}"><i class="fa fa-bar-chart"></i> Training Statistics Data Export</a></li>
    <li class="{{ $active == 'training_notify_templates' ? 'active' : '' }}"><a href="{!! route('trainingNotifyTemplates.index') !!}"><i class="fa fa-envelope"></i> Training Notify Templates</a></li>
</ul>

