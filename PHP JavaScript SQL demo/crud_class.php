<?php
/*
	This class provides some easy to use methods to connect to a database
	and perform common sql actions.  Sort of like a sql CRUD interface.
	
	Requires the PHP PDO class.
*/


class DbConn
{
	private
		$class_info		= "DbConn",
		$conjunctions	= array("AND", "OR"),
		$directions		= array("ASC", "DESC"),
		$comparisons	= array("=", "!=", ">", ">=", "<", "<=", "LIKE"),
		$table			= "",		//holds table name
		$fields			= array(),	//holds fields of table
		$where_str		= "",		//holds where clause of sql query
		$limit_str		= "",		//holds limit clause of sql query
		$sort_str		= "",		//holds sort clause of sql query
		$rows_affected	= 0;		//rows affected by previous action
	
	protected $conn	= NULL;	//PDO object
	
	
	
	/************************* "Constructor" *************************
		Creates the PDO object that the class uses to deal with the database
		requires database credentials to be passed as argument, table
		is an optional argument.
	****************************************************************************/
	public function __construct($cred=array(), $table="")
	{
		try
		{
			if(empty($cred) || !is_array($cred))
				throw new Exception("Must provide database credentials.");
			
			$dsn = 'mysql:host='.$cred['host'].';dbname='.$cred['name'];
			$this->conn		= new PDO( $dsn, $cred['user'], $cred['pass'] );
			
			$this->setTable($table);
		}
		catch(PDOException $e)
		{
			exit("PDO Exception: ".$e->getMessage());
		}
		catch(Exception $e)
		{
			die($this->class_info." __construct() exception: ".$e->getMessage());
		}
	}
	//------------------------ end Constructor ------------------------	
	
		
		
	/************************* "Utility" methods: *************************
		These methods can be used without setting the class table.
		Does not change the database/table in any way.
	****************************************************************************/
	//sets/changes the table action methods will use
	public function setTable($table="")
	{
		if(!empty($table) && is_string($table))
		{
			try
			{
				if(!$this->hasTable($table))
					throw new Exception("Table does not exist in database.");
			}
			catch(Exception $e)
			{
				die($this->class_info." setTable() method exception: ".$e->getMessage());
			}
			
			//set table string
			$this->table	= $table;
			$this->fields	= $this->fields();
		}
	}
		
	//gets a list of all tables in this database
	public function showTables()
	{
		//prepare and execute query
		$stmt		= $this->conn->prepare('SHOW TABLES');
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	
	//checks if the database contains the table specified
		//returns boolean
	public function hasTable($table="")
	{
		return in_array($table, $this->showTables());
	}
	
	//places quotes around string and escapes special characters within string
		//makes available the PHP PDO::quote method
		//according to the documentation for PDO::quote, it uses
		//"quoting style appropriate to the underlying [sql] driver"
	public function quote($string)
	{
		return $this->conn->quote($string);
	}
		
	//gets the list of fields in the table specified
		//the table in the argument takes precedence over the class table
		//return [1D/2D] array depending on details argument
	public function fields($details=false, $table_arg="", $field="")
	{
		//$table_arg overrides $this->table
		if(is_string($table_arg) && !empty($table_arg))
			$table	= $table_arg;
		else
			$table	= $this->table;
			
		try
		{
			if(empty($table))
				throw new Exception("Table needs to be set / needs table argument.");
			if(!is_string($field))
				throw new Exception("Field argument must be string");
		}
		catch(Exception $e)
		{
			die($this->class_info." fields() method exception: ".$e->getMessage());
		}
		
		$where	= "";
		if(!empty($field))
		{
			$where	.= "WHERE field = ".$this->conn->quote($field);
		}
		
		//prepare and execute query
		$stmt	= $this->conn->prepare
		("
			SHOW COLUMNS
			FROM ".$table."
			".$where."
		");
		$stmt->execute();
		
		//return resultant fields
		if($details)
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		else
			return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	
	
	//returns the auto-increment field for the very last sql insert
	public function lastInsertId()
	{
		return $this->conn->lastInsertId();
	}
	
	
	public function enums($field="", $table="")
	{
		if(is_string($field) && !empty($field))
		{
			if($field_data = $this->fields(true, $table, $field))
			{
				if(count($field_data)==1)
				{
					$type		= $field_data[0]['Type'];
					
					if(substr($type, 0, 4)=="enum")
					{
						$enums	= substr($type, 6, -2);	//strips "enum('" and "')"
						return explode("','", $enums);
					}
				}
			}
		}
	}
		
	//clears all sql query settings
	public function clearQuerySetting()
	{
		$this->clearWhere();
		$this->clearLimit();
		$this->clearSort();
	}
	
	//clears where clause
	public function clearWhere()
	{
		$this->where_str	= "";
	}
		
	//clears limit clause
	public function clearLimit()
	{
		$this->limit_str	= "";
	}
		
	//clears sort clause
	public function clearSort()
	{
		$this->sort_str		= "";
	}
		
	//get number of rows affected by last "action"
		//returns integer
	public function rowsAffected()
	{
		return $this->rows_affected;
	}
	//------------------------ end "Utility" methods ------------------------------	
	
	
	
	
	/************************* "Query Setting" methods: *************************
		Sets the sql query clauses.  Allows more control over queries.
		Does not change the database/table in any way.
		All query setting methods performs data sanitation.
	****************************************************************************/
	//creates the "WHERE" clause of the sql query
	public function where($values=array(), $comparison="=", $inner_conj="AND", $front_conj="AND")
	{
		try
		{
			if(!is_array($values))
				throw new Exception("Values must be array.");
			if(empty($front_conj) || !in_array(strtoupper($front_conj), $this->conjunctions))
				throw new Exception("Front conjunction doesn't exist or is invalid.");
			if(empty($inner_conj) || !in_array(strtoupper($inner_conj), $this->conjunctions))
				throw new Exception("Inner conjunction doesn't exist or is invalid.");
			if(empty($comparison) || !in_array(strtoupper($comparison), $this->comparisons))
				throw new Exception("Comparison doesn't exist or is invalid.");
		}
		catch(Exception $e)
		{
			die($this->class_info." where() method exception: ".$e->getMessage());
		}
		
		//generate where clause
		if(!empty($values))
		{
			$where	= "";
			$has_valid	= false;
			foreach($values as $field=>$value)
			{
				if(in_array($field, $this->fields))
				{
					$has_valid	= true;
					$where	.= " `".$field."`";
					
					if($value===NULL)
					{
						if($comparison=="!=")
							$where	.= " IS NOT NULL";
						else
							$where	.= " IS NULL";
					}
					else
					{
						$where .= " ".$comparison." ";
						if($comparison=="LIKE")
							$where	.= $this->conn->quote("%".$value."%");
						else
							$where	.= $this->conn->quote($value);
					}
					
					$where	.= " ".$inner_conj;
				}
			}
			$where	= preg_replace("/".$inner_conj."$/", "", $where);	//strips last inner_conj
			if($has_valid)
			{
				if(empty($this->where_str))	//begin where clause
					$where	= "WHERE (".$where.")";
				else	//append more conditons
					$where	= " ".$front_conj." (".$where.")";
			}
			
			//set where query string
			$this->where_str	.= $where;
		}
			
		return $this;
	}
	
	//creates a specific type of "WHERE" clause using "LIKE" operator
	public function like($string="", $inner_conj="OR", $front_conj="AND")
	{
		if(is_string($string) && !empty($string))
		{
			$values	= array();
			foreach($this->fields as $f)
			{
				$values[$f]	= $string;
			}
			$this->where($values, "LIKE", $inner_conj, $front_conj);
		}
		return $this;
	}
		
	//creates the "ORDER BY" keyword string
		//should only be used by select() method
	public function sort($items=array())
	{
		try
		{
			if(!is_array($items))
				throw new Exception("Items must be an array.");
		}
		catch(Exception $e)
		{
			die($this->class_info." sort() method exception: ".$e->getMessage());
		}
		
		if(!empty($items))
		{
			//set sort query string
			$sort	= "";
			if(empty($this->sort_str))
				$sort	= "ORDER BY ";
			foreach($items as $field=>$dir)
			{
				if(in_array($field, $this->fields) && in_array(strtoupper($dir), $this->directions))
					$sort	.= " `".$field."` ".$dir.",";
			}
			$sort	= preg_replace("/,$/", "", $sort);	//strips last comma ','
				
			//set sort query string
			$this->sort_str	= $sort;
		}
		
		return $this;
	}
		
	//creates the "LIMIT" clause of the query
		//should only be used by select() method
	public function limit($start=0, $length=30)
	{
		try
		{
			if($start<0)
				throw new Exception("Start must be an integer and be >= 0.");
			if($length<=0)
				throw new Exception("Length must be an integer and be > 0.");
		}
		catch(Exception $e)
		{
			die($this->class_info." limit() method exception: ".$e->getMessage());
		}
			
		//generate and set limit query string
		$this->limit_str	= "LIMIT ".$start.", ".$length;
		
		return $this;
	}
	//------------------------ end "Query Setting" methods ------------------------------	
		
	
	
		
	/************************* "Action" methods: *************************
		Performs sql action on the table set in the class using query settings
		generated by the query setting methods.		
		All action methods require the object table to be set.
		**Some methods may alter the database/table.**
		Except for *query* method, all action methods sanitizes data
	****************************************************************************/
	//selects rows from the table
		//fields argument is an array("field1", "field2"...) of desired return fields
		//fetch argument decides if you get the field name as key in return array
		//returns 2D array
	public function select($fields=array(), $fetch="ASSOC")
	{
		try
		{
			if(empty($this->table))
				throw new Exception("Table must be set.");
		}
		catch(Exception $e)
		{
			die($this->class_info." select() method exception: ".$e->getMessage());
		}
		
		//formats return fields
		$field_str	= "*";
		if(is_array($fields) && !empty($fields))
		{
			$field_str	= implode(", ", $fields);
		}
		
		//prepare and execute query
		$stmt	= $this->conn->prepare
		("
			SELECT ".$field_str."
			FROM ".$this->table."
			".$this->where_str."
			".$this->sort_str."
			".$this->limit_str."
		");
		
			
		$stmt->execute();
		$this->rows_affected	= $stmt->rowCount();
		
		//reset query settings
		$this->clearQuerySetting();
		
		//return results
		if(strtoupper($fetch)=="ASSOC")
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		else
			return $stmt->fetchAll(PDO::FETCH_NUM);
	}
	
	//gets number of rows in the database that'd affected by where clause
		//returns integer
	public function rows()
	{
		try
		{
			if(empty($this->table))
				throw new Exception("Table must be set.");
		}
		catch(Exception $e)
		{
			die($this->class_info." rows() method exception: ".$e->getMessage());
		}

		//prepare and execute query
		$stmt	= $this->conn->prepare
		("
			SELECT count(*)
			FROM ".$this->table."
			".$this->where_str."
		");
		$stmt->execute();
		$this->rows_affected	= $stmt->rowCount();
		
		//reset query settings
		$this->clearQuerySetting();
		
		return $stmt->fetch(PDO::FETCH_COLUMN);
	}
		
	//deletes rows in the table
		//WILL ALTER THE TABLE
		//where clause must be set before using this method
	public function delete()
	{
		try
		{
			if(empty($this->table))
				throw new Exception("Table must be set.");
			if(empty($this->where_str))
				throw new Exception("Where clause must be set.");
		}
		catch(Exception $e)
		{
			die($this->class_info." delete() method exception: ".$e->getMessage());
		}
			
		//prepare and execute query
		$stmt	= $this->conn->prepare
		("
			DELETE
			FROM ".$this->table."
			".$this->where_str."
		");
		$exec_response			= $stmt->execute();
		$this->rows_affected	= $stmt->rowCount();
		
		//reset query settings
		$this->clearQuerySetting();
		
		return $exec_response;
	}
	
	//updates rows in the table
		//WILL ALTER THE TABLE
		//values argument must be an array("field"=>"update value")
	public function update($values=array())
	{
		try
		{
			if(empty($this->table))
				throw new Exception("Table must be set.");
			if(!is_array($values))
				throw new Exception("Values must be array.");
		}
		catch(Exception $e)
		{
			die($this->class_info." update() method exception: ".$e->getMessage());
		}
		
		$exec_response	= false;
		if(!empty($values))
		{
			//generate update set string
			$set_str	= "";
			foreach($values as $field=>$value)
			{
				if(in_array($field, $this->fields))
				{
					$set_str	.= " `".$field."`=";
					if($value===NULL)
						$set_str	.= "NULL,";
					else
						$set_str	.= $this->conn->quote($value).",";
				}
			}
			$set_str	= preg_replace("/,$/", "", $set_str);	//strips last comma ','
			
			
			//prepare and execute query
			$stmt	= $this->conn->prepare
			("
				UPDATE ".$this->table."
				SET ".$set_str."
				".$this->where_str."
			");
			$exec_response			= $stmt->execute();
			$this->rows_affected	= $stmt->rowCount();
		}
		
		//reset query settings
		$this->clearQuerySetting();
		
		return $exec_response;
	}
		
	//inserts rows into the table
		//WILL ALTER THE TABLE
		//values argument must be an array("field"=>"new value")
	public function insert($new_values=array())
	{
		try
		{
			if(empty($this->table))
				throw new Exception("Table must be set.");
			if(!is_array($new_values))
				throw new Exception("Values must be array.");
		}
		catch(Exception $e)
		{
			die($this->class_info." insert() method exception: ".$e->getMessage());
		}
		
		$exec_response	= false;
		if(!empty($new_values))
		{
			$fields	= $values = array();
			foreach($new_values as $field=>$value)
			{
				if(in_array($field, $this->fields) && isset($value))
				{
					$fields[]	= $field;
					$values[]	= $this->conn->quote($value);
				}
			}			
			
			$stmt	= $this->conn->prepare
			("
				INSERT INTO ".$this->table."
					(`".implode('`, `', $fields)."`)
				VALUES
					(".implode(', ', $values).")
			");
			$exec_response			= $stmt->execute();
			$this->rows_affected	= $stmt->rowCount();
		}
			
		//reset query settings
		$this->clearQuerySetting();
		
		return $exec_response;
	}

	//runs custom sql query
		//NO DATA SANITATION is performed by this method 
		//USER MUST PERFORM DATA SANITATION
		//HIGHLY SUGGESTED to use quote() method to SANITIZE INPUT
		//does not require table to be set
		//returns 2D array
	public function query($string="")
	{
		$stmt	= $this->conn->prepare($string);
		$stmt->execute();
		$this->rows_affected	= $stmt->rowCount();
			
		//reset query settings
		$this->clearQuerySetting();
		
		return $stmt->fetchAll();
	}
	//------------------------ end "Action" methods ------------------------
}//end DbConn class
?>