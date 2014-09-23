$(document).ready(function(){
	var $table,		//dataTable object
		ajax_url	= "index.api.php",
		ajax_type	= "POST";
	
	
	$.ajaxSetup({
		"shliuErrorHandling"	: true,
		url						: ajax_url,
		type					: ajax_type,
		dataType				: "json"
		});
		
				
	$("#tab").tabs();
	
	
	$(":input.add-new, :input.add-contact").button();
		
		
	$table	= $("#customer_table").dataTable({
		"aaSorting"			: [[0, 'desc']],		//initial sorting column - starts from 0
		"bAutoWidth"		: false,
		"aLengthMenu"		: [ 50, 100, 200 ],		//available pagination options
		"iDisplayLength"	: 50,					//initial pagination
		"sPaginationType"	: "full_numbers",
		"bProcessing"		: true,		//displays the "loading" message while waiting for ajax
		"bServerSide"		: true,		//offloads logic to server side
		//url of server side processing
		"sAjaxSource"		: ajax_url,
		//does extra stuff with ajax call
		"fnServerData"		: function(sSource, aoData, fnCallback){
			//to use POST, we gotta do this fun stuff...
			$.ajax({
				"data"				: aoData,
				"success"			: function(json){
					fnCallback(json);
					}
				});
			},
		//send additional parameters to server
		"fnServerParams"	: function(aoData){
			var $form = $("#table_form");
			$.merge( aoData, $form.serializeArray() );
			},
		//do stuff with row on render
		"fnRowCallback"		: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
			$(nRow)
				.data("id", aData[0]);	//assigns first col's data to this row's data('id')
			return nRow;
			}
		});
	
	
	$(":input.add-new").on("click", function(){
		var $form	= $("#dialog").find("form");
		
		//clear/reset dialog for use
		$form.formUtil("clear");
		$form.find("[name='street']").qtip("destroy");
		$(".remove-contact").trigger("click");
		$("table.contacts").trigger("toggle-show");
		
		$("#dialog").dialog({
			modal	: true,
			width	: 800,
			title	: "New Customer",
			buttons	: {
				"Add Customer"		: function(event){
					$(event.currentTarget)
						.attr("disabled", true)
						.find("span.ui-button-text")
							.addClass("italic")
							.text("Adding...");
							
					$.ajax({
						data		: "action=insert&"+$form.serialize(),
						complete	: function(){
							$table.fnDraw();
							$("#dialog").dialog("close");
							}
						});
					},
				"Cancel"	: function(){
					$(this).dialog("close");
					}
				}
			});
		});
	
	
	$("table.display").on("click", "tbody td:not(.dataTables_empty)", function(event){
		var id			= $(this).closest("tr").data("id"),
			table		= $(this).closest("table.display").attr("id"),
			$form		= $("#dialog").find("form");
		
		//clear/reset dialog for use
		$form.formUtil("clear");
		$form.find("[name='street']").qtip("destroy");
		$(".remove-contact").trigger("click");
		$("table.contacts").trigger("toggle-show");
		
		//get list of contacts and insert into contacts table
		$.ajax({
			data		: {
				"action"	: "get_all",
				"table"		: "tbl_customer_contacts",
				"where"		: {"customer_id" : id}
				},
			beforeSend	: function(){
				//add load spinner
				$(".add-contact").after(
					$("<img>", {
						"src"	: "sliu/ajax-loader-spinner.gif",
						"alt"	: "loading contacts...",
						"class"	: "contact-loading"
						})
					);
				},
			success		: function(contacts){
				if(contacts){
					$.each(contacts, function(row, contact){
						$("table.contacts").trigger("append-contact", contact);
						});
					}
				},
			complete	: function(){
				$("img.contact-loading").remove();
				}
			});
		
		
		$.ajax({
			data	: {
				"action"	: "by_id",
				"id"		: id
				},
			success	: function(json){
				$("#dialog").find("form").formUtil("insert", json);
				
				$("#dialog").dialog({
					modal	: true,
					width	: 800,
					title	: "Customer ID: "+id,
					buttons	: {
						"Update Customer"		: function(event){
							$(event.currentTarget)
								.attr("disabled", true)
								.find("span.ui-button-text")
									.addClass("italic")
									.text("Updating...");
							
							$.ajax({
								data		: "action=update&"+$form.serialize(),
								complete	: function(){
									$("#dialog").dialog("close");
									$table.fnDraw();
									}
								});
							},
						"Cancel"	: function(){
							$(this).dialog("close");
							}
						}
					});
				}
			});
		});
		
		
	$(":button.add-contact").on("click", function(){
		var $form	= $("#contact_dialog").find("form"),
			id		= $(this).find(":input[name='id']").val();
		
		$form.formUtil("clear");
		
		$("#contact_dialog").dialog({
			title	: "New Contact",
			modal	: true,
			width	: 400,
			buttons	: {
				"Add Contact"		: function(){
					var contact	= {
						"first_name"	: $form.find("[name='first_name']").val(),
						"last_name"		: $form.find("[name='last_name']").val(),
						"phone_area"	: $form.find("[name='phone_area']").val(),
						"phone_prefix"	: $form.find("[name='phone_prefix']").val(),
						"phone_suffix"	: $form.find("[name='phone_suffix']").val(),
						"email"			: $form.find("[name='email']").val(),
						"notes"			: $form.find("[name='notes']").val()
						};
						
					$("table.contacts").trigger("append-contact", [contact]);		
					
					$(this).dialog("close");
					},
				"Cancel"	: function(){
					$(this).dialog("close");
					}
				}
			});
		//add new row onto the contact list
		});
		
		
	//contact events
	$("table.contacts")
		.on("click", ".remove-contact", function(){
			$(this).closest("tr").remove()
			$("table.contacts").trigger("toggle-show");
			})
		.on("toggle-show", function(){
			var rows	= $(this).find("tbody tr").length;
			
			if(rows<=0){
				$(this).hide();
				}
			else{
				$(this).show();
				}
			})
		.on("append-contact", function(event, data){
			var rows		= $(this).find("tbody tr").length,
				phone_all	= "("+data['phone_area']+") "+data['phone_prefix']+"-"+data['phone_suffix'];
			
			//add new row to contact list
			var $new_row	= $("<tr>")
				.append($("<td>", {"text" : data['first_name']}))
				.append($("<td>", {"text" : data['last_name']}))
				.append($("<td>", {"text" : phone_all}))
				.append($("<td>", {"text" : data['email']}))
				.append($("<td>", {"text" : data['notes']}))
				.append($("<td>").append(
					$("<input>", {
						"type"	: "button",
						"value"	: "Remove",
						"class"	: "remove-contact"
						})
					))
				.append($("<input>", {
					"type"	: "hidden",
					"name"	: "contact["+rows+"][first_name]",
					"value"	: data['first_name']
					}))
				.append($("<input>", {
					"type"	: "hidden",
					"name"	: "contact["+rows+"][last_name]",
					"value"	: data['last_name']
					}))
				.append($("<input>", {
					"type"	: "hidden",
					"name"	: "contact["+rows+"][phone_area]",
					"value"	: data['phone_area']
					}))
				.append($("<input>", {
					"type"	: "hidden",
					"name"	: "contact["+rows+"][phone_prefix]",
					"value"	: data['phone_prefix']
					}))
				.append($("<input>", {
					"type"	: "hidden",
					"name"	: "contact["+rows+"][phone_suffix]",
					"value"	: data['phone_suffix']
					}))
				.append($("<input>", {
					"type"	: "hidden",
					"name"	: "contact["+rows+"][email]",
					"value"	: data['email']
					}))
				.append($("<input>", {
					"type"	: "hidden",
					"name"	: "contact["+rows+"][notes]",
					"value"	: data['notes']
					}));
			
			$(this).find("tbody")
				.append($new_row);
				
			$(this).trigger("toggle-show");
			});
			
		
		
	//uses google maps to api for an "auto-complete" feature
	$("#dialog").on("change", "[name='street']", function(){
		var $street	= $(this);
		
		$.ajax({
			url			: "proxy.php",
			type		: "GET",
			data		: {
				"remoteURL"	: "http://maps.googleapis.com/maps/api/geocode/json",
				"sendData"	: {
					"address"	: $street.val(),
					"sensor"	: false
					}
				},
			success		: function(json){
				var qtip_content;
				
				if(json["status"]=="OK"){
					var $addresses = $("<div>");
								
					$.each(json["results"], function(row, address){
						var $address	= $("<div>",{
								"class"				: "tooltip-address",
								"text"				: address["formatted_address"]
							});
							
						$address.data("components", address["address_components"]);
							
						$addresses.append($address);
						});
						
					qtip_content	= $addresses;
					}
				else{	//bad data
					qtip_content	= "This address seems to be invalid.";
					}
					
					
				$street.qtip({
					show		: {
						ready	: true
						},
					hide		: {
						delay	: 250,
						fixed	: true		//allows interaction of inner content
						},
					content		: {
						title	: {
							text	: "Google Geocache Response:"
							},
						text	: qtip_content
						},
					position	: {
						my	: "left top",
						at	: "right center"
						},
					style: {
						classes	: 'ui-tooltip-tipped ui-tooltip-shadow'
						}
					});
					
				}
			});
			
		});
		
		
		
	//must be bound to document or something static, because div.tooltip-address's
	//are all dynamically generated
	$(document).on("click", "div.tooltip-address", function(){
		var components	= $(this).data("components"),
			street		= "";
			
		$.each(components, function(row, component){
			switch(component["types"][0]){
				case "street_number":
					street	= component["long_name"]+" "+street;
					break;
				case "route":
					street	= street+component["long_name"];
					break;
				case "locality":
					$("#dialog").find("input[name='city']").val(component["long_name"]);
					break;
				case "administrative_area_level_1":
					$("#dialog").find("input[name='state']").val(component["long_name"]);
					break;
				case "postal_code":
					$("#dialog").find("input[name='zip']").val(component["long_name"]);
					break;
				}
			});
			
		//$("#dialog").find("input[name='street']").val(street);
		})
		
		
	
	//add qtip to rows that does not contain empty empty cells
	$("table.display").on("hover", "tbody tr:not(:has(.dataTables_empty))", function(event){
		var customer_id	= $(this).closest("tr").data("id");
		
		$(this).qtip({
			overwrite	: false,		//required for .on()/.live()/.delegate()
			show		: {
				event	: event.type,	//passing event that triggered this
				ready	: true			//show as soon as it's bound
				},
			content		: {
				title	: {
					text	: "Customer ID: "+customer_id
					},
				text	: $("<a>", {
					"text"		: "Check out the address auto-complete feature!",
					//"href"		: "http://google.com"
					})
				},
			style: {
				classes	: 'ui-tooltip-tipped ui-tooltip-shadow'
				},
			position	: {
				my		: 'bottom right',
				at		: 'top left',
				target	: $(this),
				adjust	: {
					x	: 175,
					y	: 10
					}
				},
			hide		: {
				delay	: 250,
				fixed	: true		//allows interaction of inner content
				},
			events		: {
				hide	: function(event, api){
					$(this).qtip("destroy");	//kill self after use to prevent memory leak
					}
				}
			});
		});
	});