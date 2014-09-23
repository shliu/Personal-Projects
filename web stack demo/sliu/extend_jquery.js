/*
	Extends jQuery with my own addons.  You must include this file _after_ 
		the jQuery library and _before_ your page's javascript code.
	Some of these addons require additional support files.  See method
		description for details.
	Calling these addons work just like any other build-in jQuery method.
	IE:
		$("input, textarea").placeHolder();
		$("form").formUtil("clear");
		
	Requires jQuery v1.7+
*/
	
	
	
/*
	Custom error handling into $.ajax().
	
	Only when custom $.ajax option "qxErrorHandling" is set to true
*/
(function($){
	$.ajaxPrefilter(function(options){
		
		if(options.qxErrorHandling){
			//overrides user implemented .success function
			var originalSuccess	= options.success;
			
			options.success	= function(json){
				if(json && json['qx_error']){
					//if response contains an error, do error handling
					alert(json['qx_error']['message']);
					
					if(json['qx_error']['type']){
						switch(json['qx_error']['type']){
							case "session":
								window.location.href	= 
									document.location.protocol+'//'+document.location.hostname;
								break;
							}
						}
					}
				else{
					//if no error, do user implemented success function
					if(originalSuccess)
						originalSuccess(json);
					}
				}
		}
		});
	})(jQuery);
	
	
	
/*
	.ajax() modificiation (loading message box)
	
	Requires "style.css" stylesheet
*/
(function($){
	$.ajaxPrefilter(function(options){
		var originalBeforeSend	= options.beforeSend,
			originalComplete	= options.complete,
			loading_class		= "ajax-loading",
			loading_img			= "ajax-loading-img",
			loading_text		= "ajax-loading-text";
			
		//insert and/or show loading box
		options.beforeSend	= function(jqXHR, settings){
			var $loading	= $("div."+loading_class);
			
			if($loading.length<=0){
				$loading_div	= $("<div>", {
					"class"	: loading_class
					});
					
				$img		= $("<span>", {
					"class"	: loading_img
					});
					
				$text		= $("<span>", {
					"text"	: "Loading...",
					"class"	: loading_text
					});
				
				$loading_div.append($img).append($text);
					
				$(document.body).prepend($loading_div);
				}
				
			$loading.show();
			
			if(originalBeforeSend)
				originalBeforeSend(jqXHR, settings);
			}
			
		//hide loading box
		options.complete	= function(jqXHR, textstatus){
			var $loading_div	= $("div."+loading_class);
			
			$loading_div.hide();
			
			if(originalComplete){
				originalComplete(jqXHR, textstatus);
				
				}
			}
		});
	})(jQuery);
	

	
/*
	.formUtil()
	
	Utility tool to fill/clear/handle forms and form elements
*/
(function($){
	$.fn.formUtil	= function(method){
		var FORM_elements	= "input, textarea, select",
			FORM_buttons	= ":button, :reset, :submit";
		
		var	methods		= {
			//general method to clear form or form elements
			"clear"			: function(){
				return this.each(function(){
					var $node	= $(this);
					if($node.is("form")){
						//technically could use the javascript method form.reset(),
						//but it won't work if there are any form elements with
						//name='reset' or id='reset'
						$node.find(FORM_elements).formUtil("clear_elem");
						}
					else if($node.is(FORM_elements)){
						$node.formUtil("clear_elem");
						}
					});
				},
			//clears form elements only
			//behavior of clear depends on element type
			"clear_elem"	: function(){
				return this.each(function(){
					$elem	= $(this);
					if(!$elem.is(FORM_buttons)){
						if($elem.is("[type='radio'], [type='checkbox']")){
							$elem.prop("checked", false);
							}
						else if($elem.is("select")){
							$elem.find("option").prop("selected", false);
							}
						else{
							$elem.val("");
							}
						}
					});
				},
			//modifies form elements using data passed to it
			//behavior of modification depends on element type
			"insert"		: function( data ){
				return this.each(function(){
					var $node	= $(this),
						type	= $.type(data);
					
					if($node.is("form") && type=='object'){
						$.each(data, function(key, val){
							$node.find("[name='"+key+"']")
								.formUtil("insert_elem", val);
							});
						}
					else if($node.is(FORM_elements) && type=='string'){
						$node.formUtil("insert_elem", data);
						}
					});
				},
			//modifies form element using data passed to it
			"insert_elem"	: function( value ){
				return this.each(function(){
					var $elem	= $(this);
					
					if($elem.is(":input[type='radio']")){
						if($elem.val()==value)
							$elem.prop("checked", true);
						else
							$elem.prop("checked", false);
						}
					else if($elem.is(":input[type='checkbox']")){
						if($elem.val()==value)
							$elem.prop("checked", true);
						}
					else if($elem.is(FORM_elements)){
						$elem.val(value);
						}
					});
				}
			};
			
		if(methods[method]){
			//apparently .apply is a javascript method that calls the method then passes
			//'this' and [array] as argument
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
			}
		else if( typeof method==='object' || !method ){
			//default .formUtil method to call if no params or param is an object
			//return methods['on'].apply( this, arguments );
			return this;
			}
		else{
			//bad param name
			$.error("\""+method+"\" is not a valid method in .formUtil()!");
			}
		};
	})(jQuery);
	
	

/*
	.placeholder()
	
	Displays placeholder text for certain form elements (input[type='text'], textarea)
	The placeholder text is pulled from the element's [placeholder] attribute
	This is a built-in html5 feature if the browser supports it
	
	Requires "style.css" stylesheet
*/
(function($){
	var PH_elements		= "input[type='text'][placeholder], textarea[placeholder]",
		PH_css_class	= "placeholder-active",
		PH_namespace	= ".placeholder";
		
	var methods		= {
		//general method to turn on and activate .placeholder()
		//can be used for forms and form elements
		"on"			: function(){
			return this.each(function(){
				var $node	= $(this);
					
				if($node.is("form")){
					$node.find(PH_elements).placeholder("on_elem");
					}
				else if($node.is(PH_elements)){
					$node.placeholder("on_elem");
					}
				});
			},
		//turns on and activates .placeholder() for form elements
		"on_elem"		: function(){
			return this.each(function(){
				var $elem		= $(this),
					placeholder	= $elem.attr("placeholder");
				
				$elem
					.on("focusin"+PH_namespace, function(){
						if($elem.val()==placeholder){
							$elem
								.removeClass(PH_css_class)
								.val("");
							}
						})
					.on("focusout"+PH_namespace, function(){
						if($elem.val()==''){
							$elem
								.addClass(PH_css_class)
								.val(placeholder);
							}
						else
							$elem.removeClass(PH_css_class);
						})
					.placeholder("trigger");
				});
			},
		//general method to 'trigger' .placeholder()
		//manually activates .placeholder() events on form or form elements
		//'trigger' does nothing if .placeholder() has been turned off
		"trigger"		: function(){
			return this.each(function(){
				$node	= $(this);
				
				if($node.is("form")){
					$node.find(PH_elements)
						.trigger("focusout"+PH_namespace);
					}
				else if($node.is(PH_elements)){
					$node
						.trigger("focusout"+PH_namespace);
					}
				});
			},
		//general method to clear out any default text
		//does _not_ turn off .placeholder() events
		"clear"		: function(){
			return this.each(function(){
				var $node	= $(this);
				
				if($node.is("form")){
					$node.find(PH_elements).placeholder("clear_elem");
					}
				else if($node.is(PH_elements)){
					$node.placeholder("clear_elem");
					}
				});
			},
		//clears default text on form elements
		//does _not_ turn off .placeholder() events
		"clear_elem"	: function(){
			return this.each(function(){
				var $elem	= $(this);
				if($elem.val()==$elem.attr("placeholder")){
					$elem.val("");
					}
				});
			},
		//returns an object formatted in $form.serializeArray(), but without
		//the default text left in any form element with .placeholder activated
		"form_data"		: function(type){
			var $form	= this.eq(0);
			
			if($form.is("form")){
				$form.find(PH_elements).each(function(){
					$form.placeholder("clear");
					});
				data	= $form.serializeArray();
				$form.find(PH_elements).each(function(){
					$form.placeholder("trigger");
					});
				return data;
				}
			},
		//general method to turn off .placeholder() events and 
		//remove default text.  can be used on forms and form elements
		"off"			: function(){
			return this.each(function(){
				var $node	= $(this);
				
				if($node.is("form")){
					$node.find(PH_elements).placeholder("off_elem");
					}
				else if($node.is(PH_elements)){
					$node.placeholder("off_elem");
					}
				});
			},
		//removes .placeholder() events and default text on form elements only
		"off_elem"		: function(){
			return this.each(function(){
				var $elem	= $(this);
				
				$elem
					.placeholder("clear_elem")
					.removeClass(PH_css_class)
					.off(PH_namespace);
				});
			}
		};
		
	//handling of method calls of .placeholder
	$.fn.placeholder	= function(method){
		if(methods[method]){
			//apparently .apply is a javascript method that calls the method then passes
			//'this' and [array] as argument
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
			}
		else if( typeof method==='object' || !method ){
			//default .placeholder method to call if no params or param is an object
			return methods['on'].apply( this, arguments );
			}
		else{
			//bad param name
			$.error("\""+method+"\" is not a valid method in .placeholder()!");
			}
		};
	})(jQuery);