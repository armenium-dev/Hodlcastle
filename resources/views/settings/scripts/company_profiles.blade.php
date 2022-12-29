<script type="text/javascript">
	$(document).ready(function(){
		let FJS = {
			elements: {
				js_profiles_list: $('#js_profiles_list'),
				js_terms_list: $('#js_terms_list'),
				js_loader: $('#js_loader'),
				js_response_message: $('#js_response_message'),
			},
			Init: function(){
				$(document)
					//.on('submit', '.js_form', FJS.Form.submit)
					.on('click', '.js_add_row', FJS.Rows.add)
					.on('click', '.js_delete_row', FJS.Rows.remove);
			},
			Rows: {
				add: function(e, $target){
					e.preventDefault();

					if($target === undefined){
						$target = $($(e.target).data('target'));
					}

					let $item = $target.find('.js_list_item:last-child').clone(true);

					$item
						.find('input[type="text"]').val("")
						.end()
						.find('input[type="hidden"]').val("")
						.end()
						.find('input[type="checkbox"]').attr("checked", false);
					$target.append($item);
				},
				remove: function(e){
					e.preventDefault();

					let $parent = $($(e.target).data('parent'));
					const type = $(e.target).data('type');

					if($parent.find('.js_list_item').length === 1){
						FJS.Rows.add(null, $parent);
					}

					const id = $(this).parents('.js_list_item').find('input[type="hidden"]').val();
					console.log(id);

					if(id !== ''){
						FJS.Rows.remoteRemove(id, type, $(this).parents('.js_list_item'));
                    }else{
					    $(this).parents('.js_list_item').remove();
                    }

				},
                remoteRemove: function(id, type, $el_to_remove){
					$.ajax({
						type: "POST",
						url: '{!! route('settings.company_profiles.destroy') !!}',
						headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
						data:  {'id': id, 'type': type},
						dataType: "json",
						beforeSend: function(xhr){
							FJS.elements.js_loader.addClass('show');
						}
					}).done(function(response){
						if(response.error == 0){
							$el_to_remove.remove();
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
