jQuery(document).ready(function() {

	$('.contributors-item .links a').click( function() {
		$(this).parents('.contributors-item').find('.committers').slideToggle();
		return false;
	});

	$('.contributors-item .committer a').click( function() {
		$(this).next().slideToggle();
		return false;
	});

});