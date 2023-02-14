<div class="table-responsive pr">
	<div id="js_loader" class="loader2"></div>
	<table id="js_table" class="ajaxdata table">
		<thead>
			<tr>
				<th class="js_sorting sort-desc" data-field="action" sorted="1" order="desc">Action</th>
				<th class="js_sorting sort-desc" data-field="action" sorted="1" order="desc">IP Address</th>
				<th class="js_sorting sort-desc" data-field="action" sorted="1" order="desc">Time</th>
			</tr>
		</thead>
		<tbody>
			@include('activities.table-rows')
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$(document).ready(function(){

		const doSorting = function(e){
			e.preventDefault();
			e.stopPropagation();

			let $this = $(this);
			let tag_name = $this.prop('tagName');
			let url = '{!! route('profile.activities.ajax') !!}';
			let $js_loader = $('#js_loader');
			let $js_table = $('#js_table');
			let $js_search_form = $('#js_search_form');
			let order = 'desc';
			let field = 'send_date';
			let export_to_csv = false;


			if(tag_name == 'TH'){
				field = $this.data('field');
			}else{
				const $field = $js_table.find('thead').find('.js_sorting[sorted="1"]');
				field = $field.data('field');
				order = $field.attr('order');
			}

			//console.log(field, order);

			if(tag_name == 'A'){
				export_to_csv = true;
			}

			if(tag_name == 'TH'){
				if(!$this.hasClass('sort-desc') && !$this.hasClass('sort-asc')){
					order = 'asc';
				}else if($this.hasClass('sort-asc')){
					order = 'desc';
				}else if($this.hasClass('sort-desc')){
					order = 'reset';
				}
			}

			$.ajax({
				type: "GET",
				url: url,
				headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
				data: {
					name: field,
					dir: order,
					form_data: $js_search_form.serialize(),
					export: export_to_csv
				},
				dataType: "json",
				beforeSend: function(xhr){
					$js_loader.addClass('show');
				}
			}).done(function(response){
				if(response.error == 0){
					if(tag_name == 'A'){
						openInNewTab(response.link, '');
					}else{
						$js_table.find('tbody').html(response.content);
					}

					if(tag_name == 'TH'){
						$js_table.find('thead').find('.js_sorting').attr('sorted', '0').attr('order', '').removeClass('sort-desc sort-asc sort-reset');
						$this.attr('sorted', '1').attr('order', order).addClass('sort-' + order);
					}

					$js_loader.removeClass('show');
				}
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

		const resetForm = function(e){
			$('#js_search_form').find('input[type="text"]').val('');
			$('#js_search_form').find('select').val('');
			doSorting(e);
		};

		$(document)
				.on('submit', '#js_search_form', doSorting)
				.on('click', '.js_sorting', doSorting)
				.on('click', '#js_form_reset', resetForm)
				.on('click', '#js_export', doSorting);
	});
</script>
