$(document).ready(function () {

    $('.btn-check').on('click', function(){

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
        $('.btn-next').attr("disabled", false);
        $('.btn-next').css("pointer-events", "auto");

    });

});