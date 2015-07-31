$(document).ready(function () {
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

    $(".data").on("click", function () {
        var action = $(this).attr('data-action');
        $("form").attr('action', action);
    });
    
    $("select").change(function(){
        if($("#choose") == 'View By Day'){
            window.location.href = '';
        }
    });
    
});

