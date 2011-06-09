jQuery(document).ready(function($) {
    $('#back-to-top').click(function() {
        $.scrollTo($('body'), 600);
        return false;
    });
    $(window).scroll(function() {
        if($(window).scrollTop() >= 100) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
});