<?php
require_once( dirname(__FILE__).'/crud_class.php' );


$DB_CRED	= array(
	"host" => '',
	"name" => '',
	"user" => '',
	"pass" => ''
	);

try
{
	switch($_POST['action'])
	{
		case "get_table":
			//gets entire table's worth of data, also includes some filters set by DataTables
			$Table	= new DbConn($DB_CRED, "view_customers");
			$fields	= json_decode($_POST['fields']);
			
			$search_str	= $_POST['sSearch'];
			
			//generate where
			$match	= array();
			if( !empty($_POST['col_filter_field']) && is_array($_POST['col_filter_field']) )
			{
				foreach( $_POST['col_filter_field'] as $key => $field )
				{
					$match[$field] = $_POST['col_filter_value'][$key];
				}
			}
					
			//generate sort
			$sort		= array();
			for($i=0; $i<intval($_POST['iSortingCols']); $i++)
			{
				$col_num	= intval($_POST['iSortCol_'.$i]);
				if($_POST['bSortable_'.$col_num]=="true")
				{
					$sort[$fields[$col_num]] = $_POST['sSortDir_'.$i];		
				}
			}
				
			echo json_encode( array(
				"sEcho"					=> intval($_POST['sEcho']),
				"iTotalRecords"			=> $Table->rows(),
				"iTotalDisplayRecords"	=> $Table
											->like($search_str)
											->where($match)
											->rows(),
				"aaData"				=> $Table
											->like($search_str)
											->where($match)
											->sort($sort)
											->limit($_POST['iDisplayStart'], $_POST['iDisplayLength'])
											->select($fields, "COL")
				) );
			break;
		case "by_id":
			//gets single line of data
			$Customers	= new DbConn($DB_CRED, "view_customers");
			$match_id	= array(
				"id"	=> $_POST['id']
			);
			$customer	= $Customers->where($match_id)->select();
			
			if(empty($customer))
			{
				throw new Exception("This customer doesn't exist!");
			}
			else
			{
				echo json_encode($customer[0]);
			}
			break;
		case "update":
			//update data
			$Customers			= new DbConn($DB_CRED, "tbl_customers");
			$match_id	= array(
				"id"	=> $_POST['id']
			);
			
			$values	= array(
				"business_name"	=> $_POST['business_name'],
				"first_name"	=> $_POST['first_name'],
				"last_name"		=> $_POST['last_name'],
				"notes"			=> $_POST['notes'],
				"phone_area"	=> $_POST['phone_area'],
				"phone_prefix"	=> $_POST['phone_prefix'],
				"phone_suffix"	=> $_POST['phone_suffix'],
				"street"		=> $_POST['street'],
				"city"			=> $_POST['city'],
				"state"			=> $_POST['state'],
				"zip"			=> $_POST['zip']
			);
			$Customers->where($match_id)->update($values);
			
			
			//also update the contact list for this customer
			$CustomerContacts	= new DbConn($DB_CRED, "tbl_customer_contacts");
			$match_customer_id	= array(
				"customer_id"	=> $_POST['id']
			);
			$CustomerContacts->where($match_customer_id)->delete();
			if(isset($_POST['contact']) && is_array($_POST['contact']))
			{
				foreach($_POST['contact'] as $contact)
				{
					$new_contact	= array(
						"customer_id"	=> $_POST['id'],
						"first_name"	=> $contact['first_name'],
						"last_name"		=> $contact['last_name'],
						"phone_area"	=> $contact['phone_area'],
						"phone_prefix"	=> $contact['phone_prefix'],
						"phone_suffix"	=> $contact['phone_suffix'],
						"email"			=> $contact['email'],
						"notes"			=> $contact['notes']
					);
					$CustomerContacts->insert($new_contact);
				}
			}
			break;
		case "insert":
			//insert data into table
			$now				= new DateTime();
			$Customers			= new DbConn($DB_CRED, "tbl_customers");
			
			$new_customer	= array(
				"business_name"	=> $_POST['business_name'],
				"first_name"	=> $_POST['first_name'],
				"last_name"		=> $_POST['last_name'],
				"notes"			=> $_POST['notes'],
				"phone_area"	=> $_POST['phone_area'],
				"phone_prefix"	=> $_POST['phone_prefix'],
				"phone_suffix"	=> $_POST['phone_suffix'],
				"street"		=> $_POST['street'],
				"city"			=> $_POST['city'],
				"state"			=> $_POST['state'],
				"zip"			=> $_POST['zip'],
				"created_on"	=> $now->format("Y-m-d H:i:s")
			);
			$Customers->insert($new_customer);
			$new_customer_id	= $Customers->lastInsertId();

			$CustomerContacts	= new DbConn($DB_CRED, "tbl_customer_contacts");
			if(isset($_POST['contact']) && is_array($_POST['contact']))
			{
				foreach($_POST['contact'] as $contact)
				{
					$new_contact	= array(
						"customer_id"	=> $new_customer_id,
						"first_name"	=> $contact['first_name'],
						"last_name"		=> $contact['last_name'],
						"phone_area"	=> $contact['phone_area'],
						"phone_prefix"	=> $contact['phone_prefix'],
						"phone_suffix"	=> $contact['phone_suffix'],
						"email"			=> $contact['email'],
						"notes"			=> $contact['notes']
					);
					$CustomerContacts->insert($new_contact);
				}
			}
			break;
		case "get_all":
			$Table	= new DbConn($DB_CRED, $_POST['table']);
			
			$match	= (!empty($_POST['where']))
				? $_POST['where'] 
				: array();
				
			if( $results = $Table->where($match)->select() )
			{
				echo json_encode($results);
			}
			break;
	}
}
catch(Exception $e)
{
	$message	= array(
		"shliu_error"	=> array(
			"message"	=> $e->getMessage()
		)
	);
	echo json_encode($message);
}
?>
