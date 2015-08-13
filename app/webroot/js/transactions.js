$(document).ready(function () {
//choose date input use datepicker
    $(function () {
        $('.date_input').datepicker({
            inline: true,
            showOtherMonths: true
        })
                .datepicker('widget').wrap('<div class="ll-skin-nigran"/>');
    });
});
