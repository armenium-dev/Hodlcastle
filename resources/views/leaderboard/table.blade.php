<div class="table-responsive pr">
	<div id="js_loader" class="loader2"></div>
	<table id="js_table" class="leaderboard table">
		<thead>
			<tr>
				<th>#</th>
				<th class="js_sorting sort-desc" data-field="send_date" sorted="1" order="desc">Send date</th>
				<th class="js_sorting sort-reset" data-field="first_name" sorted="0" order="">First Name</th>
				<th class="js_sorting sort-reset" data-field="last_name" sorted="0" order="">Last Name</th>
				<th class="js_sorting sort-reset" data-field="email" sorted="0" order="">Email</th>
				<th class="js_sorting sort-reset" data-field="phone" sorted="0" order="">Phone number</th>
				<th class="js_sorting sort-reset text-right" data-field="mails_sent" sorted="0" order="">Mails sent</th>
				<th class="js_sorting sort-reset text-right" data-field="reported_phishes" sorted="0" order="">Reported phishes</th>
				<th class="js_sorting sort-reset text-right" data-field="phished" sorted="0" order="">Phished</th>
				<th class="js_sorting sort-reset text-right" data-field="phish_rate" sorted="0" order="">Phish rate</th>
				<th class="js_sorting sort-reset text-right" data-field="reporting_rate" sorted="0" order="">Reporting rate</th>
				<th class="js_sorting sort-reset text-right" data-field="sms_sent" sorted="0" order="">SMS sent</th>
				<th class="js_sorting sort-reset text-right" data-field="smished" sorted="0" order="">Smished</th>
				<th class="js_sorting sort-reset text-right" data-field="smish_rate" sorted="0" order="">Smish rate</th>
				<th>Department</th>
				<th>Location</th>
			</tr>
		</thead>
		<tbody>
			@include('leaderboard.table-rows')
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
			let url = '{!! route('leaderboard.ajaxsort') !!}';
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
			doSorting(e);
		};

		$(document)
				.on('submit', '#js_search_form', doSorting)
				.on('click', '.js_sorting', doSorting)
				.on('click', '#js_form_reset', resetForm)
				.on('click', '#js_export', doSorting);
	});
</script>