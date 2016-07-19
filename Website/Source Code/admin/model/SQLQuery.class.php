<?php

class SQLQuery {
    protected $_dbHandle;
    protected $_result;

    /** Connects to database **/

    function connect($address, $account, $pwd, $name) {
        $this->_dbHandle = @mysql_connect($address, $account, $pwd);
        if ($this->_dbHandle != 0) {
            if (mysql_select_db($name, $this->_dbHandle)) {
                return 1;
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }
    }

    /** Disconnects from database **/

    function disconnect() {
        if (@mysql_close($this->_dbHandle) != 0) {
            return 1;
        }  else {
            return 0;
        }
    }

    function selectAll() {
    	$query = 'select * from `'.$this->_table.'`';
    	return $this->query($query);
    }

    function select($columnName,$id) {
    	$query = 'select * from `'.$this->_table.'` where `'.$columnName.'` = \''.mysql_real_escape_string($id).'\'';
    	return $this->query($query, 1);
    }

	function selectQuery($sqlQuery) {
    	return $this->executeQuery($sqlQuery);
    }
    /** Custom SQL Query **/
	
	function executeQuery($query){
		$arrResult = array();
		$result = mysql_query($query);
		
		$i=0;
		while ($row = mysql_fetch_array($result)) {
			//echo $row[$i]." ---- ".$i."<br>";
			//echo $row[$i];
			$arrResult[] = $row;
			$i++;
		}
		//print_r($arrResult);
		//echo "count".mysql_num_rows($result);
		return $arrResult;
	}
	

	function selectQueryForAssoc($sqlQuery) {
    	return $this->executeQueryForAssoc($sqlQuery);
    }
    /** Custom SQL Query **/
	
	function executeQueryForAssoc($query){
		$arrResult = array();
		$result = mysql_query($query);
		
		$i=0;
		while ($row = mysql_fetch_assoc($result)) {
			//echo $row[$i]." ---- ".$i."<br>";
			//echo $row[$i];
			$arrResult[] = $row;
			$i++;
		}
		//print_r($arrResult);
		//echo "count".mysql_num_rows($result);
		return $arrResult;
	}
	
    /** Get number of rows **/
    function getNumRows($query) {
		$this->result = mysql_query($query);
        return mysql_num_rows($this->result);
    }

    /** Free resources allocated by a query **/

    function freeResult() {
        mysql_free_result($this->_result);
    }

	
 /** For running insert query **/	
	function query($qry){  
	 $this->query[] = $qry;  
	 $res = mysql_query($qry);  
	 if(mysql_error()){  
	  //$this->throw_error();  
	  echo 'error';
	 }  
	 return $res;  
	}
	
	
	
 /**Insert data using query **/

	function insert($val,$table){  
		 $query = 'INSERT INTO '.$table.' (';  
		  foreach ($val AS $key => $value)  
		   $query .= '`'.$key.'`,';  
		 $query = rtrim($query, ',').') VALUES (';  
		  foreach ($val AS $key => $value){  
		  	//echo $key."==".$value."<br>";
			if($key=="created_date"){
			  	$query .= mysql_real_escape_string($value).','; 
			} else if($key=="modified_date"){
			  	$query .= mysql_real_escape_string($value).','; 
			} else if($key=="requested_date"){
			  	$query .= mysql_real_escape_string($value).','; 
			} else if($key=="approved_date"){
			  	$query .= mysql_real_escape_string($value).','; 
			} else if($key=="email_time"){
			  	$query .= mysql_real_escape_string($value).','; 
			} else if($key=="comment_date"){
			  	$query .= mysql_real_escape_string($value).','; 
			} else {
		  		$query .= '\''.mysql_real_escape_string($value).'\','; 
			}
		   /*if(get_magic_quotes_gpc())  
			$query .= '\''.$value.'\',';  
		   else  
		   $query .= '\''.mysql_real_escape_string($value).'\','; */  
		  } 
		 $query = rtrim($query, ',').')';  
		 //echo $query."<br>";
		 return $this->query($query);  
	}  

	 /**Update data using query **/
	function update($val,$table,$con){  
	try 
		{
		 if($con!="")
			 {  
			   $where = "where ";
			   $lastitem = end($con);
			   //echo count($con)."<Br>";
			    if(isset($con['invoice_id']) && !empty($con['invoice_id'])){
					$expCheckWithIn = explode(",", $con['invoice_id']);
				} else if(isset($con['msg_id']) && !empty($con['msg_id'])){
					$expCheckWithIn = explode(",", $con['msg_id']);
				} else {
					$expCheckWithIn = 0;
				}
				
				$i=0;
				 foreach ($con AS $key => $value)
				 {  
					if(count($expCheckWithIn)>1 && $value!=$lastitem) {
						$i=100;//this is flag for last else condition
						$where .= $key." IN (".mysql_real_escape_string($value).") && ";
					} else if($value!=$lastitem){  
						$i=100;//this is flag for last else condition
						$where .= $key."='".mysql_real_escape_string($value)."' && ";  
					} else {					
						if((count($con)-1)>$i){  
							$where .= $key."='".mysql_real_escape_string($value)."' && ";  
						} else {
							$where .= $key."='".mysql_real_escape_string($value)."'";
						}
						$i++;
					}  
				}  
			 }  
			 else  
			 {  
			  $where = "";  
			 }  
			 $query = 'update `'.$table.'` set ';  
			 foreach ($val AS $key => $value){
				$checkedValue =  $this->checkMysqlExceptions(mysql_real_escape_string($value));
				if($checkedValue){
					$query .= $key."=".$value.',';
				} else {
					$query .= $key."=".'\''.mysql_real_escape_string($value).'\',';				
				}
				  /*if(get_magic_quotes_gpc())  
				   $query .= $key."=".'\''.$value.'\',';  
				  else  
				  $query .= '\''.mysql_real_escape_string($value).'\',';  */
			 }  
			 
			$query = rtrim($query, ',')." ".$where;  
			//echo $query;
			return $this->query($query);  
			}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	 /**
     * A method to facilitate easy bulk inserts into a given table.
     * @param string $table_name
     * @param array $column_names A basic array containing the column names
     *  of the data we'll be inserting
     * @param array $rows A two dimensional array of rows to insert into the
     *  database.
     * @param bool $escape Whether or not to escape data
     *  that will be inserted. Default = true.
     * @author Kenny Katzgrau <katzgrau@gmail.com>
     */
     function checkMysqlExceptions($pValue){
		try 
		{
			$arrExcepetion = array("NOW()", "CONCAT");
			$result = $this->array_find($pValue, $arrExcepetion);
			return $result;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	function array_find($needle, array $haystack)
	{
		try 
		{
			foreach ($haystack as $key => $value) {
				if (strpos("PRE".$needle, $value)) {
					return strpos("PRE".$needle, $value);
				}
			}
			return 0;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    function insert_rows($table_name, $column_names, $rows, $escape = true)
    {
        /* Build a list of column names */
        $columns    = array_walk($column_names, array($this, 'prepare_column_name') );
        $columns    = implode(',', $column_names);

        /* Escape each value of the array for insertion into the SQL string */
        if( $escape ) array_walk_recursive( $rows, array( $this, 'escape_value' ) );
        
        /* Collapse each rows of values into a single string */
        $length = count($rows);
        for($i = 0; $i < $length; $i++) 
			$rows[$i] = implode(',', $rows[$i]);
		

        /* Collapse all the rows into something that looks like
         *  (r1_val_1, r1_val_2, ..., r1_val_n),
         *  (r2_val_1, r2_val_2, ..., r2_val_n),
         *  ...
         *  (rx_val_1, rx_val_2, ..., rx_val_n)
         * Stored in $values
         */
        $values = "(" . implode( '),(', $rows ) . ")";

        $sql = "INSERT INTO $table_name ( $columns ) VALUES $values";

        return $this->query($sql);
    }
	
    function escape_value(& $value)
    {
        if( is_string($value) )
        {
            $value = "'" . mysql_real_escape_string($value) . "'";
        }
    }

    function prepare_column_name(& $name)
    {
        $name = "`$name`";
    }
    function delete($table,$con){
		try 
		{
			if($con!=""){
				$where = "where ";
				$lastitem = end($con);
				if(isset($con['msg_id']) && !empty($con['msg_id'])){
					$expCheckWithIn = explode(",", $con['msg_id']);
				} else {
					$expCheckWithIn = 0;
				}
				$i=0;
				foreach ($con AS $key => $value){				
					if(count($expCheckWithIn)>1 && $value!=$lastitem) {
						$i=100;//this is flag for last else condition
						$where .= $key." IN (".mysql_real_escape_string($value).") && ";
					} else if($value!=$lastitem){
						$i=100;//this is flag for last else condition
						$where .= $key."='".mysql_real_escape_string($value)."' && ";
					}
					else{			
						if((count($con)-1)>$i){  
							$where .= $key."='".mysql_real_escape_string($value)."' && ";  
						} else {
							$where .= $key."='".mysql_real_escape_string($value)."'";
						}
						$i++;
					} 
				}
			}
			else {
				$where = "";
			}
			$query = "delete from {$table} {$where}";
			//echo $query;
			return $this->query($query);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
    /** Get error string **/

    function getError() {
        return mysql_error($this->_dbHandle);
    }
}
?>