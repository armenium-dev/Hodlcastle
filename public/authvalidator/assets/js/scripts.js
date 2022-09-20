(function($){
	'use strict';
	/*window.addEventListener('load', function() {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
				if(form.checkValidity() === false){
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');

				var email = $('input[name="email"]').val();
				console.log(email);

				return false
			}, false);
		});
	}, false);*/

	var LP = {
		els: {
			form: null,
		},
		Init: function(){
			LP.els.form = $('form.validation');
		},
		submitForm: function(event){
			event.preventDefault();
			event.stopPropagation();

			var error = LP.actionValidateForm();

			//$(this).checkValidity();

			console.log(error);

			if(error == 0){
				console.log('Form submited');
				alert('Form submited');
			}
		},
		actionValidateForm: function(){
			var error = 0;
			var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
			var pass_min_lendth = 6;

			LP.els.form.find('input:required, select:required, [required]').each(function(){
				//if(error) return error;
				var $this = $(this);
				var val = $this.val();
				var type = $this.attr('type');

				//console.log(type, val);
				switch(type){
					case 'email':
						var apos = val.indexOf("@");
						var dotpos = val.lastIndexOf(".");
						//console.log(apos, dotpos);
						if(!$.trim(val).match(pattern) || apos < 1 || dotpos < 4){
							$this.addClass('is-invalid').removeClass('is-valid');
							error++;
						}else{
							$this.removeClass('is-invalid').addClass('is-valid');
						}
						break;
					case 'password':
						if((val == '')){
							$this.addClass('is-invalid').removeClass('is-valid');
							error++;
							$this.next('.invalid-tooltip').text('Password field must not be empty');
						}else if(val.length < pass_min_lendth){
							$this.addClass('is-invalid').removeClass('is-valid');
							error++;
							$this.next('.invalid-tooltip').text('The password min length ' + pass_min_lendth + ' symbols');
						}else if((val.search(/[a-zA-Z]+/) == -1) || (val.search(/[0-9]+/) == -1)){
							$this.addClass('is-invalid').removeClass('is-valid');
							error++;
							$this.next('.invalid-tooltip').text('The password must contain at least one numeral');
						}else{
							$this.removeClass('is-invalid').addClass('is-valid');
						}
						break;
					case 'text':
						break;
					case undefined:
						break;
				}
			});

			//LP.els.form.addClass('was-validated');

			return error;
		},
	};


	$(document)
		.ready(LP.Init)
		.on('click change keyup', 'form.validation input', LP.actionValidateForm)
		.on('submit', 'form.validation', LP.submitForm);

})(jQuery);
