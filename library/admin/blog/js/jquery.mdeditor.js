/**
 * @ Dependency CSS : Bootstrap, Font-awesome
 * @ Dependency JS : jQuery, jQuery.form.js, jQuery.bootstrap.js, showdown.js
 */
;(function($){
	var
	$mdeditor = null,
	$mdtools = null,
	$mdarea = null,
	$mdform = null,
	$mt = null, // textarea
	$mp = null, // preview
	$input_md_contents = null,
	$input_md_files = null,

	// 초기화!!
	settings = {
		'baseurl' : '.',
		'formaction' : '.',
		'vendor' : {
			'markdown' : function(input) { return input }
		}
	},
	staticVars = {}
	;
	var init = function( obj ) {
		$input_md_contents = $( 'input.mdeditor-contents' );
		$input_md_files = $( 'input.mdeditor-files' );
		$mdeditor	= $( '<div />' ).addClass('mdeditor').insertAfter( obj );
		$mdtools	= $( '<div />' ).addClass('mdeditor-tools').appendTo( $mdeditor );
		$mdarea		= $( '<div />' ).addClass('mdeditor-area').appendTo( $mdeditor );
		$mt = obj.addClass('mdeditor-textarea').appendTo( $mdarea );
		$mp = $( '<div />' ).addClass('mdeditor-preview').appendTo( $mdarea );

		// Tools Added
		$( '<div class="btn-group"><a class="btn mdeditor-func-upload-image" title="Image Upload"><i class="icon-picture"></i></a></div>' ).appendTo( $mdtools );

		// Tools Added Form
		$mdform = $( '<form method="post" enctype="multipart/form-data">' ).html(
			'<input type="hidden" name="action" value="fileuploader" />' +
			'<div id="mdeditor_modal_upload_image" class="modal fade hide">' +
				'<div class="modal-header">' +
					'<button type="button" data-dismiss="modal" class="close">&times;</button>' +
					'<h4>File Uploader</h4>' +
				'</div>' +
				'<div class="modal-body">' +
					'<div class="btn wdu-input-file">' +
						'<i class="icon-file"></i>' +
						'<span>Select File..</span>' +
						'<input type="file" name="fileupload[]" class="mdeditor-modal-upload-image-btn" multiple />' +
					'</div>' +
					'<div class="mdeditor-files-list"></div>' +
				'</div>' +
				'<div class="modal-footer">' +
					'<a href="#" data-dismiss="modal" class="btn">닫기</a>' +
				'</div>' +
			'</div>'
		).attr("action", settings.formaction).appendTo( 'body' );
		
		$input_md_files.each(function() {
			$( 'div.mdeditor-files-list' ).append( 
				$('<a/>').html( $(this).val() ).attr({'data-url' : settings.baseurl + "/" + $(this).val() })
			);
		});
		$mp.html( $input_md_contents.val() );
		$mt.bind("keyup keydown", convertToPreview);
		$mt.bind("keydown", _keydownTab);

		$mdtools.find( 'a.mdeditor-func-upload-image' ).bind("click", funcUploadImage);

		$mdform.ajaxForm({
			dataType : 'json',
			beforeSend : function() {
				$( 'div.mdeditor-files-list' ).before( $('<div class="progress progress-striped active" id="file-progress"><div class="bar" style="width:0%;"></div></div>') );
			},
			uploadProgress : function(event, position, total, percentComplete) {
				$( '#file-progress > div.bar' ).css({'width' : percentComplete + "%"});
			},
			success : function(response, statusText, xhr, $form) {
				console.log(response);
				for (var i = 0; i < response.data.length; i++ ) {
					$( 'div.mdeditor-files-list' ).prepend( 
						$('<a/>').html( response.data[i] ).attr({'data-url' : response.url[i]})
					);
					$mdeditor.after( $('<input type="hidden" name="files[]" class="mdeditor-files" value="' + response.data[i] + '" />') );

				}
				$( '#file-progress' ).remove();
			}
		});
		$( '.mdeditor-modal-upload-image-btn' ).bind('change', function() {
			$mdform.submit();
		});
		$mdform.find( 'div.mdeditor-files-list' ).on("click", "a", function() {
			$( '#mdeditor_modal_upload_image' ).modal('hide');
			$mt[0].value += "![" + $(this).text() + "](" + $(this).data('url') + ")";
			$mt.trigger("keyup");
		});
	};

	var convertToPreview = function() {
		var contents = settings.vendor.markdown( $(this).val() );
		$mp[0].innerHTML = contents;
		$input_md_contents.val(contents);
	};

	var funcUploadImage = function() {
		$( '#mdeditor_modal_upload_image' ).modal( 'show' );
	};

	var _keydownTab = function(e) {
		if (e.keyCode === 9) {
			var start = this.selectionStart;
			var end = this.selectionEnd;

			var $this = $(this);
			var value = $this.val();

			// set textarea value to: text before caret + tab + text after caret
			$this.val(value.substring(0, start) + "\t" + value.substring(end));

			// put caret at right position again (add one for the tab)
			this.selectionStart = this.selectionEnd = start + 1;

			// prevent the focus lose
			e.preventDefault();
		}
	};


	$.fn.mdeditor = function( opt ) {
		$.extend(settings, opt);
		init(this);
		return this;
	};

})(jQuery);