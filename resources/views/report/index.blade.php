@extends('layouts.report')

@section('content')
    <div class="nav-tabs-custom layout">
        @include('main.tabs', ['active' => 'generatereport'])
        <div class="tab-content pr">
            @include('flash::message')

            <div class="clearfix"></div>

            <section class="max-w-300">
				<div id="js_loader" class="loader2"></div>
				<form id="js_generate_pdf_form" method="post" action="" class="mb-20">
					@csrf
					<input id="js_chart_image_data" type="hidden" name="chart_image_data">
                    <div class="row">
                        <div class="col-sm-12 mb-20">
                            <label>Start date</label>
                            <input type="text" name="start_date" class="datepicker form-control" data-min="2019-01-01" data-start="" data-end="" data-val="">
                        </div>
                        <div class="col-sm-12 mb-20">
                            <label>End date</label>
                            <input type="text" name="end_date" class="datepicker form-control" data-min="2019-01-01" data-start="" data-end="" data-val="">
                        </div>
                        @if(Auth::user()->hasRole('captain'))
                        <div class="col-sm-12 mb-20">
                            <label>Company</label>
                            <select name="company" class="form-control">
                                @foreach($companies as $company)
                                    <option value="{!! $company->id !!}">{!! $company->name !!}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-success me-10" value="Generate PDF report">
                        </div>
                    </div>
                </form>
				<a id="js_link_result" class="d-block" href="#" download=""></a>
			</section>

			<div id="js_chart_result" class="chart-result"></div>

        </div>
    </div>

    <script type="text/javascript">
		$(document).ready(function(){
			let generated = false;

			const doSubmit = function(e){
				e.preventDefault();
				e.stopPropagation();

				let $this = $(this);
				let url = '{!! route('generatereport.ajaxGetChartContent') !!}';
				let $js_loader = $('#js_loader');
				let $js_result = $('#js_chart_result');
				let $js_form = $('#js_generate_pdf_form');

				$.ajax({
					type: "POST",
					url: url,
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data: $js_form.serialize(),
					dataType: "json",
					beforeSend: function(xhr){
						$js_loader.addClass('show');
					}
				}).done(function(response){
					if(response.error == 0){
						//openInNewTab(response.link, '');
						$js_result.html(response.content);
						generated = false;
						initChart();
					}
					$js_loader.removeClass('show');
				}).fail(function(response){
					$js_loader.removeClass('show');
					console.log(response.error);
				});
			};

			const initChart = function(){
				var $chartReport = $('#chartReport');

				if ($chartReport.length == 0)
					return;

				//console.log($chartReport);
				var labels_str = $chartReport.data('labels');
				var labels = labels_str.split(',');
				var short_labels_str = $chartReport.data('short-labels');
				var short_labels = short_labels_str.split(',');

				var data_sents = $chartReport.data('data-sents');
				var data_opens = $chartReport.data('data-opens');
				var data_clicks = $chartReport.data('data-clicks');
				var data_fake_auth = $chartReport.data('data-fakeauth');
				var data_attachments = $chartReport.data('data-attachments');
				var data_smishs = $chartReport.data('data-smishs');
				var data_reports = $chartReport.data('data-reports');

				var chartCanvas = $chartReport.get(0).getContext('2d');
				var barChart = new Chart(chartCanvas, {
					type: 'bar',
					data: {
						tooltip_labels: labels,
						labels: short_labels,
						datasets: [
							{
								label: "Clicked",
								backgroundColor: "#DD4B39",
								data: data_clicks
							},
							{
								label: "Reported",
								backgroundColor: "#56a501",
								data: data_reports
							}
						]
					},
					options: {
						title: {
							display: true,
							text: 'Click rate versus reporting rate',
							fontSize: 18,
							fontWeight: 'normal',
							fontFamily: "Helvetica",
							padding: 10
						},
						responsive: false,
						layout: {
							padding: {
								left: 0,
								right: 0,
								top: 0,
								bottom: 0
							},
						},
						legend: {
							display: true,
							position: 'right', // place legend on the right side of chart
							labels: {
								strokeStyle: '#ffffff',
								lineCap: 0,
								lineWidth: 0,
								boxWidth: 14,
								//padding: 10,
								//fontColor: "#666",
								//fontSize: 14,
								//usePointStyle: true,
							}
						},
						hover: {
							intersect: false
						},
						tooltips: {
							display: false
						},
						scales: {
							xAxes: [{
								scaleLabel: {
									display: true,
									labelString: 'Campaign',
									//fontColor: "#ff006f",
									//fontSize: 16,
									//padding: 0
								},
								//stacked: true // this should be set to make the bars stacked
							}],
							yAxes: [{
								scaleLabel: {
									display: true,
									labelString: 'Rate as a percentage of unique recipients',
									//fontColor: "#ff006f",
									//fontSize: 16,
									padding: 10
								},
								//stacked: true // this also..
							}]
						},
						animation: {
							onComplete: function(){
								var image = barChart.toBase64Image();
								//console.log(image);
								$('#js_chart_image_data').val(image);
								if(generated == false){
									getPDF();
									generated = true;
								}
							}
						}
					}
				});
			};

			const getPDF = function(){
				let url = '{!! route('generatereport.ajaxGeneratePDF') !!}';
				let $js_loader = $('#js_loader');
				let $js_result = $('#js_link_result');
				let $js_form = $('#js_generate_pdf_form');

				$.ajax({
					type: "POST",
					url: url,
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					data: $js_form.serialize(),
					dataType: "json",
					beforeSend: function(xhr){
						$js_loader.addClass('show');
					}
				}).done(function(response){
					if(response.error == 0){
						$js_result
								.text(response.filename)
								//.attr('download', response.filename)
								.attr('href', response.link);
						openInNewTab(response.link, response.filename);
					}
					$js_loader.removeClass('show');
				}).fail(function(response){
					$js_loader.removeClass('show');
					console.log(response.error);
				});
			};

			const openInNewTab = function(url, filename){
				Object.assign(document.createElement('a'), {
					target: '_blank',
					rel: 'noopener noreferrer',
					href: url,
					//download: filename,
				}).click();
			};

			$(document).on('submit', '#js_generate_pdf_form', doSubmit);
		});
    </script>
@endsection
