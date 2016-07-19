<?php
class SQLQuery {
    protected $_dbHandle;
    protected $_result;
    /** Connects to database **/
    function connect($address, $account, $pwd, $name) {
		try 
		{
			$this->_dbHandle = @mysql_connect($address, $account, $pwd) or die("mysql connection failed");
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
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
    /** Disconnects from database **/
    function disconnect() {
		try 
		{
			if (@mysql_close($this->_dbHandle) != 0) {
				return 1;
			}  else {
				return 0;
			}
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
    function selectAll() {
		try 
		{
			$query = 'select * from '.$this->_table.'';
			return $this->query($query);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
    function select($columnName,$id) {
		try 
		{
			$query = 'select * from '.$this->_table.' where `'.$columnName.'` = \''.mysql_real_escape_string($id).'\'';
			return $this->query($query, 1);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
	function selectQuery($sqlQuery) {
		try 
		{
    		return $this->executeQuery($sqlQuery);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
    /** Custom SQL Query **/
	
	function executeQuery($query){
		try{
			//echo $query;
			$arrResult = array();
			
			$result2 = mysql_query($query, $this->_dbHandle);
			$i=0;
			while ($row = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				//echo $row[$i]." ---- ".$i."<br>";
				//echo $row[$i];
				$arrResult[] = $row;
				$i++;
			}
			//print_r($arrResult);
			//echo "count".mysql_num_rows($result);
			return $arrResult;
		}catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	function selectQueryForAssoc($sqlQuery) {
		try 
		{
    		return $this->executeQueryForAssoc($sqlQuery);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
    /** Custom SQL Query **/
	
	function executeQueryForAssoc($query){
		try 
		{
			$arrResult = array();
			$result = mysql_query($query, $this->_dbHandle);
			
			$i=0;
			while ($row = @mysql_fetch_assoc($result)) {
				//echo $row[$i]." ---- ".$i."<br>";
				//echo $row[$i];
				$arrResult[] = $row;
				$i++;
			}
			//print_r($arrResult);
			//echo "count".mysql_num_rows($result);
			return $arrResult;
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
	
	
    /** Get number of rows **/
    function getNumRows($query) {
		try 
		{
			$this->result = mysql_query($query, $this->_dbHandle);
			return mysql_num_rows($this->result);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
    /** Free resources allocated by a query **/
    function freeResult() {
		try 
		{
     	   mysql_free_result($this->_result);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
	
	
 /** For running insert query **/	
	function query($qry){  
		try 
		{
			 $this->query[] = $qry;  
			 $res = mysql_query($qry, $this->_dbHandle);  
			 if(mysql_error()){  
			  $this->throw_error();  
			  echo 'error';
			 }  
			 return $res; 
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		} 
	}
	
	
 /**Insert data using query **/
	function insert($val,$table){ 
		try 
		{
			 $query = 'INSERT INTO '.$table.' (';  
			  foreach ($val AS $key => $value)  
			   $query .= '`'.$key.'`,';  
			 $query = rtrim($query, ',').') VALUES (';  
			  foreach ($val AS $key => $value){  
				$checkedValue = $this->checkMysqlExceptions(mysql_real_escape_string($value));
				if($checkedValue){
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
			 //echo $query;
			 return $this->query($query); 
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}  
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
			 $query = 'update '.$table.' set ';  
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
			//return $query;
			return $this->query($query); 
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	 
	}
	
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
	
	#-############################################# 
	# desc: does an update query with an array 
	# param: table (no prefix), assoc array with data (doesn't need escaped), where condition 
	# returns: (query_id) for fetching results etc 
	function query_update($table, $data, $where='1') { 
		try 
		{
			$q="UPDATE ".$this->pre.$table." SET "; 
			
			/*foreach($data as $key=>$val) { 
				if(strtolower($val)=='null') $q.= "`$key` = NULL, "; 
				elseif(strtolower($val)=='now()') $q.= "`$key` = NOW(), "; 
				elseif(preg_match("/^increment\((\-?\d+)\)$/i",$val,$m)) $q.= "`$key` = `$key` + $m[1], ";  
				//else $q.= "`$key`='".$this->escape($val)."', "; 
				else $q.= "`$key`=".'\''.mysql_real_escape_string($value).'\',';	
			} 
			*/
			
			 foreach ($data AS $key => $value){
				if(mysql_real_escape_string($value)=="NOW()"){
					$q .= $key."=".mysql_real_escape_string($value).',';
				} else {
					$q .= $key."=".'\''.mysql_real_escape_string($value).'\',';				
				}
			 }
			$q = rtrim($q, ', ') . ' WHERE '.$where.';'; 
			//echo $q;
			return $this->query($q); 
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}#-#query_update() 
	function escape($string) {
		try 
		{
			if(get_magic_quotes_runtime()) $string = stripslashes($string);
			return @mysql_real_escape_string($string,$this->link_id);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}#-#escape()
	
	function getInsertId()
	{
		try 
		{
			return mysql_insert_id();
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
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
	
	function throw_error(){
		try 
		{
			 if($this->debug==true){
			 $qry = "".end($this->query)."<br>";
			 }
			 else{
			 $qry = "";
			 }
			 die("Mysql Error: ".mysql_error());
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
	}
	
    /** Get error string **/
    function getError() {
		try 
		{
      	  return mysql_error($this->_dbHandle);
		}
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
    }
	
	function query_delete($table,$con)
	{
		try 
		{
			$where  = NULL;
      		foreach ($con as $item => $value) {
       		//  if (isset($fieldlist[$item]['pkey'])) {
           			 $where .= "$item='$value' AND ";
         	//} // if
      	} // foreach
      $where  = rtrim($where, ' AND ');
      $query = "DELETE FROM $table WHERE {$where}";     	
			//echo $query;
			return $this->query($query);
	 	 }
		catch ( Exception $e ) {
			echo 'Message: ' .$e->getMessage();
		}
      
	}
	
}
?>