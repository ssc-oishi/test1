// jQuery Alert Dialogs Plugin
//
// Version 1.1
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 14 May 2009
//
// Visit http://abeautifulsite.net/notebook/87 for more information
//
// Usage:
//		jAlert( message, [title, callback] )
//		jConfirm( message, [title, callback] )
//		jPrompt( message, [value, title, callback] )
// 
// History:
//
//		1.00 - Released (29 December 2008)
//
//		1.01 - Fixed bug where unbinding would destroy all resize events
//
// License:
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2008 A Beautiful Site, LLC. 
//
jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();
(function($) {
	
	$.alerts = {
		// These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time
		verticalOffset: -75,                // vertical offset of the dialog from center screen, in pixels
		horizontalOffset: 0,                // horizontal offset of the dialog from center screen, in pixels/
		repositionOnResize: true,           // re-centers the dialog on window resize
		overlayOpacity: .01,                // transparency level of overlay
		overlayColor: '#FFF',               // base color of overlay
		draggable: true,                    // make the dialogs draggable (requires UI Draggables plugin)
		okButton: '&nbsp;OK&nbsp;',         // text for the OK button
		cancelButton: '&nbsp;Cancel&nbsp;', // text for the Cancel button
		dialogClass: null,                  // if specified, this class will be applied to all dialogs
		
		// Public methods
		alert: function(message, title, callback) {
			if( title == null ) title = 'Alert';
			$.alerts._show(title, message, null		, null			, null		, null		, null	, 'alert', function(result) {
				if( callback ) callback(result);
			});
		},
		confirm: function(message, title, callback) {
			if( title == null ) title = 'Confirm';
			$.alerts._show(title, message, null		, null			, null		, null		, null	, 'confirm', function(result) {
				if( callback ) callback(result);
			});
		},
		prompt: function(message, promptEle, itype, value, title, callback) {
			if( title == null ) title = 'Prompt';
			$.alerts._show(title, message, itype	, null			, null		, promptEle	, value	, 'prompt', function(result) {
				if( callback ) callback(result);
			});
		},
		radio: function(message, radioEle, value, title, callback) {
			if( title == null ) title = 'Radio';
			$.alerts._show(title, message, null		, null			, radioEle	, null		, value	, 'radio', function(result) {
				if( callback ) callback(result);
			});
		},
		select: function(message, selectEle, value, title, callback) {
			if( title == null ) title = 'Select';
			$.alerts._show(title, message, null		, selectEle		, null		, null		, value	, 'select', function(result) {
				if( callback ) callback(result);
			});
		},
		// Private methods
		_show: function(title, msg, itype, selectEle, radioEle, promptEle, value, type, callback) {
			$.alerts._hide();
			$.alerts._overlay('show');
			$("BODY").append(
				'<div id="popup_container">' +
					'<h1 id="popup_title"></h1><div id="popup_content"><div id="popup_message"></div></div>' +
				'</div>');
			if( $.alerts.dialogClass ) $("#popup_container").addClass($.alerts.dialogClass);
			// IE6 Fix
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
			$("#popup_container").css({
				position: pos,
				zIndex: 99999,
				padding: 0,
				margin: 0
			});
			$("#popup_title").text(title);
			$("#popup_content").addClass(type);
			$("#popup_message").text(msg);
			$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );
			$("#popup_container").css({
				minWidth: $("#popup_container").outerWidth(),
				maxWidth: $("#popup_container").outerWidth()
			});
			$.alerts._reposition();
			$.alerts._maintainPosition(true);
			
			switch( type ) {
				case 'alert':
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /></div>');
					$("#popup_ok").click( function() {
						$.alerts._hide();
						callback(true);
					});
					$("#popup_ok").focus().keypress( function(e) {
						if( e.keyCode == 13 || e.keyCode == 27 ) $("#popup_ok").trigger('click');
					});
					break;
				case 'confirm':
					$("#popup_message").after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_ok").click( function() {
						$.alerts._hide();
						if( callback ) callback(true);
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback(false);
					});
					$("#popup_ok").focus();
					$("#popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					break;
				case 'prompt':
					var txtpromptEle='' ;
					var txtpromptMKR='' ;
					var arOptcnt=0  ;
					var arOpt  ;
					var arVal  ;
					if (promptEle!=""){
						arOpt=promptEle.split(',') ;
						arVal=value.split(',') ;
						arTyp=itype.split(',') ;
						arOptcnt=arOpt.length ;
						txtpromptEle='<table cellspacing=0 cellpadding=0 id="p_prompt_tbl">';
						for (var i=0; i<arOptcnt; i++) {
							txtpromptEle+="<tr><td>"+arOpt[i]+":</td><td><input type='"+arTyp[i]+"' size='30' id='popup_prompt"+i+"' value='"+arVal[i]+"'/></td></tr>" ;
							txtpromptMKR='popup_prompt'+i ;
						}
						txtpromptEle+='</table>';
					}else{
						txtpromptMKR='popup_prompt' ;
						txtpromptEle ="<br /><input type='"+itype+"' size='30' id='popup_prompt' />" ;
					}
					$("#popup_message").append(txtpromptEle+'').after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					if (promptEle!=""){
						$("#p_prompt_tbl td").css("padding", "0 4px 0 8px;");
						$("#p_prompt_tbl td").css("text-align", "right");
					}else{
						$("#popup_prompt").width( $("#popup_message").width() );
					}
					$("#popup_ok").click( function() {
						var val='' ;
						var v='' ;
						if (arOptcnt>0){
							for (var i=0; i<arOptcnt; i++) {
								if(i!=0) {
									val	= val+'\t'+$("#popup_prompt"+i).val() ; 
									v	+=$("#popup_prompt"+i).val() ; 
								}else{
									val	= $("#popup_prompt"+i).val() ;
									v	= $("#popup_prompt"+i).val() ;
								}
							}
						}else{
							val	= $("#popup_prompt").val();
							v	= $("#popup_prompt").val();
						}
						if (v!=""){
							$.alerts._hide();
							if( callback ) callback( val );
						}
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback( null );
					});
					$("[id^='popup_prompt'], #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ){
							var val='' ;
							if (arOptcnt>0){
								for (var i=0; i<arOptcnt; i++) {
									if(i!=0) {
										val = val+'\t'+$("#popup_prompt"+i).val() ; 
									}else{
										val = $("#popup_prompt"+i).val() ;
									}
								}
							}else{
								val=$("#popup_prompt").val() ;
							}
							if (val!='' && $(this).attr('id')==txtpromptMKR){
								$("#popup_ok").trigger('click');
							}else{
								e.keyCode = 9;
							}
						}
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					$("#popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_prompt").val(value);
					$("#popup_prompt").focus().select();
					break;
				case 'radio':
					var i ;
					var arOpt=radioEle.split('\n') ;
					var txtradioEle='' ;
					for (i=0; i<arOpt.length; i++) {
						txtradioEle+="<input type='radio' id='popup_radio"+i+"' name='popup_radioG' value='"+arOpt[i].split('\t')[0]+"'" + ((arOpt[i].split('\t')[0]==value) ? ' checked':'') +">"+arOpt[i].split('\t')[1] ;
					}
					$("#popup_message").append('<br />'+txtradioEle+'<input type="hidden" id="popup_radio" />').after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_radio").width( $("#popup_message").width() );
					$("#popup_ok").click( function() {
						var val = $("#popup_radio").val();
						$.alerts._hide();
						if( callback ) callback( val );
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback( null );
					});
					$("input[id^='popup_radio']").click( function() {
						$("#popup_radio").val($(this).val())
					});
					$("#popup_radio, #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_radio").val(value);
					$("#popup_radio").focus().select();
					break;
				case 'select':
					var i ;
					var arOpt=selectEle.split('\n') ;
					var txtselectEle='' ;
					txtselectEle="<select id='popup_select'>" ;
					for (i=0; i<arOpt.length-1; i++) {
						txtselectEle+="<option value='"+arOpt[i].split('\t')[0]+"'" + ((arOpt[i].split('\t')[0]==value) ? ' selected':'') +">"+arOpt[i].split('\t')[1] +"</option>" ;
					}
					$("#popup_message").append('�F'+txtselectEle+'</select><input type="hidden" id="popup_select" />').after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_select").width( $("#popup_message").width() );
					$("#popup_ok").click( function() {
						var val = $("#popup_select").val();
						$.alerts._hide();
						if( callback ) callback( val );
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback( null );
					});
					$("input[id^='popup_select']").click( function() {
						$("#popup_select").val($(this).val())
					});
					$("#popup_select, #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_select").val(value);
					$("#popup_select").focus().select();
					break;
			}
			
			// Make draggable
			if( $.alerts.draggable ) {
				try {
					$("#popup_container").draggable({ handle: $("#popup_title") });
					$("#popup_title").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
		},
		_hide: function() {
			$("#popup_container").remove();
			$.alerts._overlay('hide');
			$.alerts._maintainPosition(false);
		},
		_overlay: function(status) {
			switch( status ) {
				case 'show':
					$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay"></div>');
					$("#popup_overlay").css({
						position: 'absolute',
						zIndex: 99998,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay").remove();
				break;
			}
		},
		_reposition: function() {
			var top = (($(window).height() / 2) - ($("#popup_container").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			
			$("#popup_container").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay").height( $(document).height() );
		},
		_maintainPosition: function(status) {
			if( $.alerts.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', $.alerts._reposition);
					break;
					case false:
						$(window).unbind('resize', $.alerts._reposition);
					break;
				}
			}
		}
		
	}
	
	// Shortuct functions
	jAlert  = function(message, title, callback) {
		$.alerts.alert(message, title, callback);
	}
	
	jConfirm  = function(message, title, callback) {
		$.alerts.confirm(message, title, callback);
	};
		
	jPrompt  = function(message, promptEle, itype, value, title, callback) {
		$.alerts.prompt(message, promptEle, itype, value, title, callback);
	};
	
	jRadio  = function(message, radioEle, value, title, callback) {
		$.alerts.radio(message, radioEle, value, title, callback);
	};
	
	jdSelect = function(message, selectEle, value, title, callback) {
		$.alerts.select(message, selectEle, value, title, callback);
	};
	
})(jQuery);