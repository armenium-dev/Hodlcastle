<div class="table-responsive pr">
	<div id="js_loader" class="loader2"></div>

	{!! Form::open(['route' => 'settings.blacklisted_sms_terms.store', 'files' => false, 'id' => 'js_form']) !!}
		<ul id="js_terms_list" class="list-group terms max-w-500">
			@foreach($blacklisted_sms_terms as $k => $term)
				<li class="js_list_item list-group-item">
					<div class="col flex-1">{!! Form::text('terms[]', $term, ['class' => 'js_sms_from form-control', 'placeholder' => 'Term']) !!}</div>
					<div class="col"><a class="js_delete_row btn btn-danger" href="#"><i class="fa fa-trash"></i> Delete</a></div>
				</li>
			@endforeach
		</ul>
		<div class="btn-group d-flex align-items-center">
			{!! Form::button('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
			<a id="js_add_row" class="js_add_row btn btn-default" href="#"><i class="fa fa-plus"></i> Add new row</a>
			<span id="js_response_message" class="ms-20"></span>
		</div>

	{!! Form::close() !!}
</div>
<script type="text/javascript">
	$(document).ready(function(){
		let FJS = {
			elements: {
				js_list: $('#js_terms_list'),
				js_loader: $('#js_loader'),
				js_response_message: $('#js_response_message'),
			},
			Init: function(){
				$(document)
					.on('submit', '#js_form', FJS.Form.submit)
					.on('click', '#js_add_row', FJS.Rows.add)
					.on('click', '.js_delete_row', FJS.Rows.remove);
			},
			Rows: {
				add: function(obj){
					let $item = FJS.elements.js_list.find('.js_list_item:last-child').clone(true);
					$item.find('input[type="text"]').val("");
					FJS.elements.js_list.append($item);
				},
				remove: function(){
					if(FJS.elements.js_list.find('.js_list_item').length === 1){
						FJS.Rows.add();
                    }
                    $(this).parents('.js_list_item').remove();
				},
			},
			Form: {
				submit: function(e){
					e.preventDefault();
					e.stopPropagation();

					let $this = $(this);
					let url = $this.attr('action');

					$.ajax({
						type: "POST",
						url: url,
						headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
						data:  $this.serialize(),
						dataType: "json",
						beforeSend: function(xhr){
							FJS.elements.js_loader.addClass('show');
						}
					}).done(function(response){
						if(response.error == 0){
							FJS.elements.js_response_message.text(response.message).fadeIn(400);
							setTimeout(function(){
								FJS.elements.js_response_message.fadeOut(400);
							}, 2000);
						}
						FJS.elements.js_loader.removeClass('show');
					}).fail(function(response){
						FJS.elements.js_loader.removeClass('show');
						console.log(response.error);
					});

				},
			},
		};

		FJS.Init();
	});
</script>
