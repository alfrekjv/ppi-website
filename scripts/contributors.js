jQuery(document).ready(function() {
    var hidden = null;
    
	$('.contributors-item .links a').click( function() {
		$(this).parents('.contributors-item').find('.committers').slideToggle();

        if (!hidden) {
            $(this).html("Hide Contributors");
            hidden = true;
        } else {
            $(this).html("Show Contributors");
        }

		return false;
	});

});