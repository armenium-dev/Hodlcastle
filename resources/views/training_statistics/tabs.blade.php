<ul class="nav nav-tabs">
	<li class="{{ $active == 'trainingStatistics' ? 'active' : '' }}"><a href="{!! route('trainingStatistics.index') !!}"><i class="fa fa-dashboard"></i> Statistics</a></li>
	<li class="{{ $active == 'trainingStatisticsExport' ? 'active' : '' }}"><a href="{!! route('trainingStatistic.export') !!}"><i class="fa fa-bar-chart"></i> Data Export</a></li>
</ul>
