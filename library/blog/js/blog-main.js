;(function($){
	var
	blog_list = $( '#blog_items' ),
	blog_ajax_url = blog_list.data("ajax");

	blog_list.on("click", "div.item", function() {
		location.hash = "#!/view/" + $(this).data("idx");
	});
	var resizing = function() {
		var w = blog_list.find("div.item > div.inner").eq(0).width();
		blog_list.find("div.thumbnail-preview").each(function() {
			$(this).height( w * $(this).data('height') / 100 );
		});
	};
	resizing();
	$(window).bind("resize", resizing);

	var hashAction = function() {
		var idx = location.hash.split("/view/")[1];
		if (typeof idx == "undefined") { 
			//$( '#itemModal' ).wanimodal({"visible":"hide"})
			return;
		}
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
		},
		bufferPx : 100
	},
	function( newElements ) {
		resizing();
		var $newElems = $( newElements );
		blog_list.masonry('appended', $newElems);
	});
/*
	$(window).scroll(function() {
		if ($(window).scrollTop() >= $(document).height() - $(window).height() - 60) {
			var page_url = $( "#navi > a").attr("href");
			$.ajax({
				type : "GET",
				dataType : "html",
				url : page_url,
				success : function(data) {
					console.log( $(data).find("#blog_items div.item") );
				}
			})
		}
    });
*/
//	prettyPrint();
})(jQuery);