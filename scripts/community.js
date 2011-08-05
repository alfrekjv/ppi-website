jQuery(document).ready(function() {
	$(".topcontent .filter-twitter").click(function() {
		$(".source-github").fadeOut('fast');
		$(".source-twitter").fadeIn('fast');
		return false;
	});


	$(".topcontent .filter-github").click(function() {
		$(".source-github").fadeIn('fast');
		$(".source-twitter").fadeOut('fast');
		return false;
	});

	$(".topcontent .filter-all").click(function() {
		$(".source-github").fadeIn('fast');
		$(".source-twitter").fadeIn('fast');
		return false;
	});
});