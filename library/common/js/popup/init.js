;(function($) {

var oPopup = $( '.wandu_popup' );

var popup_list = [];

var setCookie = function ( name, value, expiredays ) {
    var todayDate = new Date();
    todayDate.setDate( todayDate.getDate() + expiredays );
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
};
var getCookie = function( name ) { 
    var nameOfCookie = name + "="; 
    var x = 0; 
    while ( x <= document.cookie.length ) 
    { 
        var y = (x+nameOfCookie.length); 
        if ( document.cookie.substring( x, y ) == nameOfCookie ) 
        { 
            if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 ) 
                endOfCookie = document.cookie.length;
            return unescape( document.cookie.substring( y, endOfCookie ) ); 
        } 
        x = document.cookie.indexOf( " ", x ) + 1; 
        if ( x == 0 ) 
            break; 
    } 
    return ""; 
};


oPopup.each(function() {
	var oThis = $(this);
	var pid = oThis.attr('id');
	
	popup_list.push( pid );
	
    if(getCookie( pid ) == "done") oThis.hide(300);
    else oThis.show();
    
    oThis.find( ".close" ).click(function() { oThis.hide(300) });
    oThis.find( ".check" ).change(function() {
    	if ( $(this).is( ":checked" ) ) {
	    	setCookie(pid, "done", 1);
    	}
    	oThis.hide();
    });
	
});


})(jQuery);