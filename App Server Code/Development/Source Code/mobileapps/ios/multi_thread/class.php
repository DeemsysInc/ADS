<?php //phpinfo();
ini_set("display_errors", 1);
include_once $_SERVER['DOCUMENT_ROOT'].'/multi_thread/test2.php';

$obj = new Test2();
echo $obj->runFunc();

?>