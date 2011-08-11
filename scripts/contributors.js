jQuery(document).ready(function() {

	$('.contributors-item .links a').click( function() {
		$(this).parents('.contributors-item').find('.committers').slideToggle();

        if ($(this).text() == "Show Contributors") {
            $(this).html("Hide Contributors");
        } else {
            $(this).html("Show Contributors");
        }

		return false;
	});

});