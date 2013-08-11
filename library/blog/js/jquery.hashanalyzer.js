;(function($){

var elems = 0;

var before_url = null;
var url = null;

var hashanalysis = function( x ) {
	
	var ret = {
		path : "."
	};

	if (x.indexOf("!") === -1) return ret;

	var items = x.split("!")[1].split("?");
	ret.path = items[0];

	if (typeof items[1] !== "undefined") {
		var querys = items[1].split("&");

		if (querys[0] !== "") ret.query = {};

		for (var i = 0; i < querys.length; i++) {

			// 아무것도 없으면 종료!
			if (querys[i] == "") break;

			if (querys[i].indexOf("=") !== -1) {
				var temp = querys[i].split("=");
				ret.query[temp[0]] = temp[1];
			}
			else {
				ret.query[querys[i]] = true;
			}
		}
	}
	return ret;
};

url = hashanalysis( location.hash );

var hashtrigger = function() {
	before_url = url;
	url = hashanalysis( location.hash );

	if ( before_url === null ) {
		
		$(window).triggerHandler("hashpathchange");
	}
	else {
		if ( before_url.path !== url.path ) {
			$(window).triggerHandler("hashpathchange");
		}
	}
};

$.event.special.hashpathchange = {
	setup : function( data, ns, eventHandle ) {
//		console.log(data);
//		console.log(ns);
//		console.log(eventHandle);
		elems++;
		if (elems === 1) {
			$(window).bind("hashchange", hashtrigger);
		}
	},
	teardown : function() {
		elems--;
		if (elems === 0) {
			$(window).bind("hashchange", hashtrigger);
		}
	}
};

$.hashanalysis = function( x ) { return hashanalysis( x ); };

$.hashpathchange = {};

$.hashpathchange.getUrl = function() { return url; };
$.hashpathchange.getLastUrl = function() { return url; };

})(jQuery);