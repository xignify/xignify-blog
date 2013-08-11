;(function($, w) {
	$( '[data-href]' ).click(function() {
		w.location.href = $(this).data('href');
	});

})(jQuery, window);