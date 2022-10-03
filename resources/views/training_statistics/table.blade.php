<div class="custom-table-data">
    <table class="table data-table table-responsive">
        <thead>
            <tr class="filters">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th width="170"></th>
            </tr>
            <tr>
                <th>Recipient</th>
                <th>Email</th>
                <th>Company</th>
                <th>Start</th>
                <th>Finish</th>
                <th>Time spent</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total:</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    $(function(){

        var table = $('.data-table').DataTable({
			//dom: '<"top"iflp<"clear">>rt',
			dom: '<"toolbar"fl>rt<"bottom"ip>',
            processing: true,
            serverSide: true,
			pageLength: 50,
            ajax: "{{ url('trainingStatistic/getTable') }}",
            columns: [
                {data: 'recipient', name: 'recipient'},
                {data: 'recipient_email', name: 'recipient_email'},
                {data: 'company', name: 'company'},
                {data: 'start_training', name: 'start_training'},
                {data: 'finish_training', name: 'finish_training'},
                {data: 'time', name: 'time'},
            ],
			initComplete: function(){
				let div = $('<div id="js_year_filter" class="dataTables_year"><label>Filter by years:</label> </div>');
				let select = $('<select><option value="">All</option></select>');

				$('div.toolbar').prepend(div);

				this.api().columns([3]).every(function(index){
					var column = this;

					select
						.appendTo($('div.toolbar #js_year_filter'))
						.on('change', function(){
							var val = $.fn.dataTable.util.escapeRegex($(this).val());
							console.log(val);
							column
								.search(val ? val : '', true, false)
								.draw();
						});

					@foreach($years as $year)
					select.append('<option value="{!! $year->value !!}">{!! $year->value !!}</option>');
                    @endforeach
				});
			},
			"fnDrawCallback": function(oSettings){
				//updateLangsFlags();
			},
			footerCallback: function (row, data, start, end, display) {
				var api = this.api();

				// Remove the formatting to get integer data for summation
				var intVal = function(i){
					return typeof i === 'string' ? i.replace(/ min/g, '') * 1 : typeof i === 'number' ? i : 0;
				};

				// Total over all pages
				let total = api.column(5).data().reduce(function(a, b){
                    return intVal(a) + intVal(b);
                }, 0);

				// Total over this page
				let pageTotal = api.column(5, {page: 'current'}).data().reduce(function(a, b){
                    return intVal(a) + intVal(b);
                }, 0);

				// Update footer
				$(api.column(5).footer()).html(pageTotal + ' mins');
			},
        });

		//$('div.toolbar').append('<b>Custom tool bar! Text/images etc.</b>');

		var updateLangsFlags = function(){
			table.rows().every(function(index){
				//console.log(index);
				var year = this.data().DT_RowAttr['data-year'];
				if(year != ''){
					var $td = $('.dataTable tbody tr:eq(' + index + ') td:eq(3)');
					$td.html(year);
				}
			});
		};

	});
</script>