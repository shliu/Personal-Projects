<?php
$fields	= array(
	"id"			=> "ID",
	"first_name"	=> "First Name",
	"last_name"		=> "Last Name",
	"address"		=> "Address",
	"phone"			=> "Phone #"
);
$fields_str	= "";
foreach($fields as $f=>$n)
{
	$fields_str	.= "<th>".$n."</th>";
}


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Steven Liu's demo page</title>
		
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<link rel='stylesheet' type='text/css' href='jquery/css/redmond/jquery-ui-1.8.16.custom.css' />
		<link rel="stylesheet" type="text/css" href="sliu/style.css" media="screen" />		
		<script type="text/javascript" language="javascript" src="jquery/jquery-1.7.2.min.js" ></script>
		<script type="text/javascript" language="javascript" src="jquery/jquery-ui-1.8.20.custom.min.js" ></script>
		
		<!-- sliu/extend_jquery.js contains the custom jquery extension -->
		<script type="text/javascript" language="javascript" src="sliu/extend_jquery.js" ></script>
		
		<link rel="stylesheet" type="text/css" href="jquery/datatables/media/css/demo_table.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="sliu/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="jquery/qtip/jquery.qtip.css" media="screen" />
		<script type="text/javascript" language="javascript" src="jquery/datatables/media/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="jquery/qtip/jquery.qtip.js"></script>
		
		<!-- index.js contains this page's front end logic -->
		<script type="text/javascript" language="javascript" src="index.js"></script>
		<style>
			input.ui-button.add-contact,
			input.ui-button.add-new {
				font-size: 80%;
				}
				
			table.display tbody tr:hover td:not(.dataTables_empty){
				background: yellow;
				cursor: pointer;
				}
			
			div.dialog {
				display: none;
				}
			div.dialog div {
				vertical-align: middle;
				}
			div.dialog legend {
				font-size: 120%;
				}
			div.dialog strong {
				display: inline-block;
				text-align: right;
				width: 120px;
				}
			div.dialog fieldset input[type='text'],
			div.dialog fieldset textarea {
				width: 500px;
				}
			div.dialog fieldset textarea {
				height: 50px;
				}
				
			table.contacts{
				width: 100%;
				border: 1px solid black;
				}
			table.contacts tbody tr:nth-child(odd){
				background: #EEEEFF;
				}
			table.contacts td{				
				text-align: center;
				}
				
			div.tooltip-address{
				margin: 5px;
				font-size: 100%;
				}
				
			div.tooltip-address:hover{
				background: yellow;
				cursor: pointer;
				}
		</style>
	</head>
	<body>
		<div id="banner">
			<em>Steven Liu</em>
		</div>
		<ul id="nav">
			<li><div>PHP, MySQL, and jQuery Demo</div></li>
		</ul>
		<div id="wrapper">
			<div id="content">
			
				<div id="tab">
					<ul>
						<li><a href="#customers">Customers</a></li>
					</ul>
					
					<div id="customers" >
						<input type="button" class="add-new" value="Add New Customer" />
						<form id="table_form" >
							<input type="hidden" name="action" value="get_table" />
							<input type="hidden" name="table" value="tbl_customers" />
							<input type="hidden" name="fields" value='<?php echo json_encode(array_keys($fields)); ?>' />
						</form>
						<table id="customer_table" class="display" >
							<thead>
								<tr>
									<?php echo $fields_str; ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="100%" class="dataTables_empty">Loading data from server</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<?php echo $fields_str; ?>
								</tr>
							</tfoot>
						</table>
						<div class="clear" ></div>
					</div>
				</div>
			
			
				
				
				
				
				<div id="dialog" class="dialog" >
					<form>
						<input type="hidden" name="id" />
						<fieldset>
							<legend>Customer:</legend>
							<div>
								<strong>First Name: </strong>
								<input type="text" name="first_name" />
							<div>
							</div>
								<strong>Last Name: </strong>
								<input type="text" name="last_name" />
							</div>
							<div>
								<strong>Notes: </strong>
								<textarea name="notes"></textarea>
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Contact Information:</legend>
							<div>
								<strong>Phone #: </strong>
								(<input type="text" name="phone_area" maxlength=3 style="width: 35px;" />)
								<input type="text" name="phone_prefix" maxlength=3 style="width: 35px;" /> - 
								<input type="text" name="phone_suffix" maxlength=4 style="width: 50px;" />
							</div>
							<div>
								<strong>Street: </strong>
								<input type="text" name="street" />
							</div>
							<div>
								<strong></strong>
								<em style="color:red">Type in a street to see the auto-complete feature using GoogleMaps API</em>
							</div>
							<div>
								<strong>City: </strong>
								<input type="text" name="city" />
							</div>
							<div>
								<strong>State: </strong>
								<input type="text" name="state" />
							</div>
							<div>
								<strong>Zip: </strong>
								<input type="text" name="zip" />
							</div>
						</fieldset>
						
						<fieldset>
							<legend>Additional Contacts:</legend>
							<input type="button" class="add-contact" value="New Contact" />
							<table class="contacts">
								<thead>
									<tr>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Phone</th>
										<th>E-mail</th>
										<th>Notes</th>
										<th></th>
									</tr>
								<thead>
								<tbody>
								</tbody>
							</table>
							<div id="contacts" ></div>
						</fieldset>
						
						<div>
							<strong>Created:</strong>
							On: <input type="text" name="created_on" disabled />
						</div>
					</form>
				</div>
				
				<div id="contact_dialog" class="dialog" >
					<form>
						<div>
							<strong>First Name: </strong>
							<input type="text" name="first_name" />
						<div>
						</div>
							<strong>Last Name: </strong>
							<input type="text" name="last_name" />
						</div>
						<div>
							<strong>Phone #: </strong>
							(<input type="text" name="phone_area" maxlength=3 style="width: 35px;" />)
							<input type="text" name="phone_prefix" maxlength=3 style="width: 35px;" /> - 
							<input type="text" name="phone_suffix" maxlength=4 style="width: 50px;" />
						</div>
						<div>
							<strong>E-Mail: </strong>
							<input type="text" name="email" />
						</div>
						<div>
							<strong>Notes: </strong>
							<textarea name="notes"></textarea>
						</div>
					</form>
				</div>
				
				
			</div><!-- content -->
		</div>
		<div id="footer">
			<div style="float:left">
				<p>Steven Liu - <?php echo date('Y'); ?></p>
			</div>
			<div style="float:right; text-align: right">
				<p>Javascript must be enabled for this page to work correctly.</p>
				<p>Firefox 3.6+ or Chrome 12+ is recommended.</p>
			</div>
		</div>
	</body>
</html>
