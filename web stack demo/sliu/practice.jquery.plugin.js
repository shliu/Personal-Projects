
(function($){
	$.fn.QxForm = function( params ){
		var $dialog = null,
			forms = {
			"booboo"	: 123456,
			"test"		: function(){
				alert( $dialog.html() );
				$dialog.dialog({
					title	: "hello world",
					buttons	: {
						"Update"	: function(){
							alert( 'updating' );
							$(this).dialog('close');
							},
						"Close"		: function(){
							$(this).dialog('close');
							}
						}
					});
				}
			};
			
			
		if( params.type ){
			var id = Math.floor( Math.random()*1000000 );
			$(document.body).append("<div id='"+id+"'>hello world</div>");
			$dialog	= $("#"+id);
			
			forms[params.type].call();
			}
		};
	})(jQuery);