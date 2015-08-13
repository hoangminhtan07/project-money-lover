$(document).ready(function () {

    //hide footer when scroll down
    var val = 0;
    $(window).scroll(function () {

        if ($(this).scrollTop() > val) {
            $('footer').removeClass('nav-up').addClass('nav-down');
        } else {
            $('footer').removeClass('nav-down').addClass('nav-up');
        }
        val = $(this).scrollTop();
    });

    //still-navTop when scroll down
    var pos = $('#navTop').offset();
    $(window).scroll(function () {
        if ($(this).scrollTop() > pos.top) {
            $('#navTop').addClass('nav-still');
        } else if ($(this).scrollTop() <= pos.top) {
            $('#navTop').removeClass('nav-still');
        }
    });

});

$(window).load(function () {
    setTimeout(function () {
        $('.alert').fadeOut(3888, 'swing')
    });
});