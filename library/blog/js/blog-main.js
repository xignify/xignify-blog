;(function($){
	var
	blog_list = $( '#blog_items' ),
	blog_ajax_url = blog_list.data("ajax");

	blog_list.on("click", "div.item", function() {
		location.hash = "#!/view/" + $(this).data("idx");
	});

	var hashAction = function() {
		var idx = location.hash.split("/view/")[1];
		$.ajax({
			type : "POST",
			dataType : "json",
			url : blog_ajax_url + idx,
			success : function(data) {
				$( '#itemModal > .subject' ).html( data.result.subject );
				$( '#itemModal > .main' ).html( data.result.contents );
				$( '#itemModal > .date' ).html( data.result.reg_date );
				$( '#itemModal' ).wanimodal();
			},
			error : function(data) {
			}
		});
	};
	hashAction();
	$(window).bind("hashchange", hashAction);
	
	blog_list.masonry();
	blog_list.infinitescroll({
		navSelector : "#navi",
		nextSelector : "#navi > a",
		itemSelector : "#blog_items div.item",
		loading : {
			finishedMsg : "No more page to load.",
			img : 'http://i.imgur.com/qkKy8.gif'
		}
	},
	function( newElements ) {
		var $newElems = $( newElements );
		blog_list.masonry('appended', $newElems);
	});
	
//	prettyPrint();
})(jQuery);