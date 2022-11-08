$(document).ready(function () {
    /*$('.btn-check').on('click', function(){
        $(".checkbox, .radio").map(function(indx, element){
            var correct_state = $(this).find('.correct').val();
            var check_input = $(this).find('.answer').prop("checked") ? 1 : 0;

            if (correct_state == 1) {
                $(element).css('color', 'green');
                $(element).css('font-weight', '600');
            }

            if (correct_state == 0 && check_input == 1) {
                $(element).css('color', 'red');
                $(element).css('font-weight', '600');
            }
        });

        $('.btn-check').attr("disabled", "disabled");
        $('.btn-next').attr("disabled", false).css("pointer-events", "auto");
    });*/

	const submitForm = function(e){
		e.preventDefault();
		e.stopPropagation();

		const $form = $(this);
		const btn_text = 'Submit <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
		let $btn_submit = $form.find('#js_submit'),
			$btn_next =	$form.find('#js_next'),
			form_data = new FormData;

		//console.log($form.serializeArray());

		$form.find('input[type="checkbox"], input[type="radio"]').each(function(i, el){
			let $el = $(el);
			console.log($el.attr('name'), $el.val());
		});

		$.ajax({
			type: "POST",
			url: $form.attr('action'),
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			data: $form.serialize(),
			dataType: "json",
			beforeSend: function(xhr){
				$btn_submit.attr('disabled', true).text('Saving...');
			}
		}).done(function(response){
			//console.log(response);

			if(response.error === 0){
				$btn_submit.addClass('hidden');
				$btn_next.attr('disabled', false);
			}else{
				$btn_submit.attr('disabled', false).html(btn_text);
			}
		}).fail(function(){
			$btn_submit.attr('disabled', false).html(btn_text);
		});

		return false;
	};

	$(document).on('submit', '#js_course_form', submitForm);
});