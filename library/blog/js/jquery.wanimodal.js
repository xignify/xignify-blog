;(function($){

	var
	$screen = $( 'html,body' ),
	$blind,
	on_ing,
	$modal_list = [],
	is_visible = false,
	default_opt = {
		beforeAnimate : null,
		afterAnimate : null,
		visible : "toggle"
	}
	;

	var showModal = function( $target, opt ) {
		if (on_ing) return;
		on_ing = true;

		$blind.show();
		if (opt.beforeAnimate != null) opt.beforeAnimate();
		$blind.append($target);
		$target.css({'top':'-100%', 'opacity':0}).show().animate({'top':'15%','opacity':1}, 300, function() {
			if (opt.afterAnimate != null) opt.afterAnimate();
			on_ing = false;
		});
		$screen.css('overflow', 'hidden');
		$blind.css('overflow', 'scroll');
		
		$blind.bind("click", hideModal);
	};
	var hideModal = function() {
		if (on_ing) return;
		on_ing = true;

		$blind.hide();
		for ( var i = 0; i < $modal_list.length; i++ ) {
			$modal_list[i].animate({'top' : '-100%','opacity':0}, 300, function() {
				$(this).hide();
				on_ing = false;
			})
			
		}
		$screen.css('overflow', 'auto');
		$blind.css('overflow', 'hidden');
		$blind.unbind("click", hideModal);
	};

	$.fn.wanimodal = function( option ) {

		var opt = $.extend(default_opt, option);
		if ( !this.hasClass("wanimodal-init") ) {
			$modal_list.push( this );
			this.addClass("wanimodal-init");		
			this.find(".close").bind("click", hideModal);
		}

		if ($blind == null) {
			$blind = $( '<div class="simple-modal-blind"></div>' ).appendTo( 'body' );
			$blind.hide();
		}
		
		if ( opt.visible == "show" ) {
			showModal( this, opt );
		}
		else if ( opt.visible == "hide" ) {
			hideModal();
		}
		else {
			if (is_visible) {
				hideModal();
			}
			else {
				showModal( this, opt );
			}
		}
		
		return this;
	};
})(jQuery);