jQuery(document).ready(function() {

	$('.contributors-item .links a').click( function() {
		$(this).parents('.contributors-item').find('.committers').slideToggle();
		return false;
	});

});