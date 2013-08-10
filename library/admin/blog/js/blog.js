;(function($){
	
	$( '#mdeditor' ).mdeditor({
		'baseurl' : $('#mdeditor').data('baseurl'),
		'formaction' : $('#mdeditor').data('action'),
		'vendor' : {
			'markdown' : markdown.toHTML
		}
	});

	$( 'div.wdu-input-file > input[type=file]' ).each(function() {
		var 
		$f_input = $(this),
		$f_btn = $(this).siblings("span"),
		origin_text = $f_btn.text();
		$f_input.bind('change', function() {
			var filename = $(this).val().split('\\');
			if (filename == "") {
				$f_btn.text( origin_text );
			}
			else {
				$f_btn.text( "File : " + filename[filename.length-1] );
			}
		});
	});


})(jQuery);