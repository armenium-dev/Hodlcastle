$(document).ready(function () {

    $(document).on('click', '.quiz-questions input:checkbox',function() {

        if ($('.quiz-select-type select').val() == 'radio') {
            if ($(this).is(':checked')) {
                $('.quiz-questions input:checkbox').not(this).prop('checked', false);
            }
        }

    });

    $(document).on('change', '.quiz-select-type select',function() {
        $('.quiz-questions input:checkbox').not(this).prop('checked', false);
    });

});