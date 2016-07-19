<?php
global $config;
//print_r($config);
/**** Include SQLQuery class for Database connection and main function ****/
//require_once 'C:/wamp/www/admin-panel/model/SQLQuery.class.php';
require_once $config['ABSOLUTEPATH'].'model/SQLQuery.class.php';


//require_once $getConfig['ABSOLUTEPATH'].'model/SQLQuery.class.php';

class hsModel extends SQLQuery {
	protected $_model;
	
	function __construct() {
		global $config;
		$this->connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
	}
	public function insertFaqQuestionAnswers($pArray,$tableName)
	{ 
	   $result = $this->insert($pArray,$tableName);
	
	   return $result;
	}
	
	function getFaqGroupNames()
	{
		$sqlQuery = "SELECT * FROM  mvc_faq_groups";
		//echo $sqlQuery;
		$result = $this->selectQuery($sqlQuery);
		//print_r($result);
		return $result;	

	}	
	function getFaqquestionsAnswers()
	{
		$sqlQuery ="SELECT * FROM  `mvc_faq` AS f LEFT JOIN  `mvc_faq_groups` AS g ON g.group_id = f.group_id ORDER BY `title` DESC";
		//echo $sqlQuery;
		$result = $this->selectQuery($sqlQuery);
		//print_r($result);
		return $result;	
	}
	public function deleteFaqQuestionAnswer($pArray)	
	{
		
		$sqlQuery  = "DELETE FROM `mvc_faq` WHERE `id` ='".$pArray['id']."'";
		//echo $sqlQuery;
		$sqlResult =  $this->selectQuery($sqlQuery);
		return $sqlResult;
	}
	public function getFaqGroupresults($pArray)	
	{
		
		$sqlQuery  = "SELECT group_id,id,title,description FROM `mvc_faq` WHERE group_id='".$pArray."'";
		//echo $sqlQuery;
		$sqlResult =  $this->selectQuery($sqlQuery);
		//print_r($sqlResult);
		return $sqlResult;
	}
	public function updateQuenAnsValue($title,$description,$tableName,$con)
	{   
	$sqlQuery ="UPDATE `mvc_faq` SET title = '".$title."',description = '".$description."' WHERE id ='".$con."'; ";
	//echo $sqlQuery;
	  	$result = $this->selectQuery($sqlQuery);
		//print_r($result);
		return $result;
	}
}

?>