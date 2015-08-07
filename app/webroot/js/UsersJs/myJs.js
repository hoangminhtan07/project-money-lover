$(document).ready(function () {

    //validation of forgotPassword modal
    $('#forgotPasswordForm').formValidation({
        framework: 'bootstrap',
        excluded: [':disabled'],
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    emailAddress: {
                        message: 'Please enter an email-address'
                    }
                }
            }
        }
    });

    //get data of form forgotPassword modal
    $('.data').on('click', function () {
        var action = $(this).attr('data-action');
        $("form").attr('action', action);
    });
});