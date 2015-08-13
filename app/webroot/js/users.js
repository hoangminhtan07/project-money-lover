$(document).ready(function () {

    //validation of forgotPassword modal
    $("#forgotPasswordForm").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        }
    });



    //get data of form forgotPassword modal
    $('.data').on('click', function () {
        var action = $(this).attr('data-action');
        $("form").attr('action', action);
    });
});
