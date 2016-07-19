<?php

include_once($_SERVER['DOCUMENT_ROOT']."/mobileapps/ios/multi_thread/Thread.php");

function function1($test_arg){
	sleep(15);
	return "Pass in variable ".$test_arg.".<br />";
}
function function2($test_arg){	
	sleep(1);
	return "2nd passing var ".$test_arg.".<br />";
}
function function3($test_arg){	
	sleep(10);
	return "3rd passing var ".$test_arg.".<br />";
}
/* function function4($test_arg){	
	sleep(10);
	return "4th passing var ".$test_arg.".<br />";
}
function function5($test_arg){	
	sleep(10);
	return "5th passing var ".$test_arg.".<br />";
}
function function6($test_arg){	
	sleep(40);
	return "6th passing var ".$test_arg.".<br />";
}
function function7($test_arg){	
	sleep(10);
	return "7th passing var ".$test_arg.".<br />";
}
function function8($test_arg){	
	sleep(10);
	return "8th passing var ".$test_arg.".<br />";
}
function function9($test_arg){	
	sleep(25);
	return "9th passing var ".$test_arg.".<br />";
}
function function10($test_arg){	
	sleep(30);
	return "10th passing var ".$test_arg.".<br />";
} */
runFunc();
function runFunc(){
		
$program_start_time = time();

$thread_1 = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);
$thread_1->setFunc("Test2::function1",array("Var 1"));

$thread_2 = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);
$thread_2->setFunc("Test2::function2",array("Var 2"));

$thread_3 = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);
$thread_3->setFunc("Test2::function3",array("Var 3"));

/* $thread_4 = new Thread("localhost",80);
$thread_4->setFunc("function4",array("Var 4"));

$thread_5 = new Thread("localhost",80);
$thread_5->setFunc("function5",array("Var 5"));

$thread_6 = new Thread("localhost",80);
$thread_6->setFunc("function6",array("Var 6"));

$thread_7 = new Thread("localhost",80);
$thread_7->setFunc("function7",array("Var 7"));

$thread_8 = new Thread("localhost",80);
$thread_8->setFunc("function8",array("Var 8"));

$thread_9 = new Thread("localhost",80);
$thread_9->setFunc("function9",array("Var 9"));

$thread_10 = new Thread("localhost",80);
$thread_10->setFunc("function10",array("Var 10")); */

$thread_1->start();
$thread_2->start();
$thread_3->start();
/* $thread_4->start();
$thread_5->start();
$thread_6->start();
$thread_7->start();
$thread_8->start();
$thread_9->start();
$thread_10->start(); */

echo $thread_1->getreturn();
echo $thread_2->getreturn();
echo "saadsad".$thread_3->getreturn();
/* echo $thread_4->getreturn();
echo $thread_5->getreturn();
echo $thread_6->getreturn();
echo $thread_7->getreturn();
echo $thread_8->getreturn();
echo $thread_9->getreturn();
echo $thread_10->getreturn();
 */

echo "<br>Main Program has run ".(time()-$program_start_time)." seconds<br />";
//}
}


?>