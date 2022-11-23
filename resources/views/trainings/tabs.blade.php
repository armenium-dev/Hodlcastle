<ul class="nav nav-tabs">
	<li class="{{ $active == 'trainings' ? 'active' : '' }}"><a href="{!! route('trainings.index') !!}"><i class="fa fa-graduation-cap"></i> Trainings</a></li>
    <li class="{{ $active == 'training_statistics' ? 'active' : '' }}"><a href="{!! route('trainingStatistics.index') !!}"><i class="fa fa-graduation-cap"></i> Training Statistics</a></li>
    <li class="{{ $active == 'training_notify_templates' ? 'active' : '' }}"><a href="{!! route('trainingNotifyTemplates.index') !!}"><i class="fa fa-graduation-cap"></i> Training Notify Templates</a></li>
</ul>
