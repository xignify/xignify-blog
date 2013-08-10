(function ($) {
if (!Modernizr.input.autofocus) { $( 'input[autofocus]' ).focus(); }
if (!Modernizr.input.placeholder) {
	$( '[placeholder]' ).focus( function() {
		var input = $(this);
		if ( input.val() == input.attr('placeholder') ) {
			input.val('');
			input.removeClass('placeholder');
		}
	}).blur(function() {
		var input = $(this);
		if ( input.val() == '' || input.val() == input.attr('placeholder') ) {
			input.addClass('placeholder');
			input.val(input.attr('placeholder'));
		}
	}).blur();
}
var login = function () {
	oForm = $(this);
	if (!Modernizr.input.placeholder) {
		oForm.find( '[placeholder]' ).each(function() {
			var input = $(this);
			if ( input.val() == input.attr('placeholder') ) {
				input.val('');
			}
		});
	}
	if (!Modernizr.input.required) {
		oForm.find( '[required]' ).each(function() {
			if( ($(this).attr('required') !== false) && ($(this).val() == "") ) {
				$(this).focus();
				alert($(this).attr("name") + "은(는) 반드시 입력하여야 합니다.");
				return false;
			}
			
		});
	}
	$.ajax({
		type : oForm.attr("method"),
		data : oForm.serialize(),
		url : oForm.attr("action"),
		success : function(data) {
			if ( data.output == true ) {
				location.reload();
			}
			else {
				$( '#container .alert' ).slideDown();
				$( 'input[name=password]' ).val('');
			}
		},
		error : function() {
			$( '#container .alert' ).slideDown();
			$( 'input[name=password]' ).val('');
		}
	});
	return false;
};
$( 'form' ).bind("submit", login);

})(jQuery); 