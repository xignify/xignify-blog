(function($){

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
var funcSubmit = function () {
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
};

$( 'form' ).bind("submit", funcSubmit);

var logout = function () {
	oThis = $(this);
	$.ajax({
		type : 'POST',
		url : oThis.data("action"),
		data : {'action' : "logout"},
		success : function(data) {
			console.log(data);
			if ( data.output != 0 ) {
				location.reload();
			}
		}
	});
};

$( "#logout" ).bind("click", logout);

})(jQuery);