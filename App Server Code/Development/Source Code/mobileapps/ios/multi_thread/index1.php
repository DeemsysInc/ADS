<?php
$output="";
include_once("Thread.php");

function function1($test_arg,$second){
	sleep(1);
	return "Pass in variable ".$test_arg.".<br />Second variable : ".$second."<BR>";
}
function function2($test_arg){	
	sleep(2);
	return "2nd passing var ".$test_arg.".<br />";
}
function function3($test_arg){	
	sleep(3);
	return "3rd passing var ".$test_arg.".<br />";
}
function function4($test_arg){	
	sleep(4);
	return "4th passing var ".$test_arg.".<br />";
}
function function5($test_arg){	
	sleep(5);
	return "5th passing var ".$test_arg.".<br />";
}
function function6($test_arg){	
	sleep(6);
	return "6th passing var ".$test_arg.".<br />";
}
function function7($test_arg){	
	sleep(7);
	return "7th passing var ".$test_arg.".<br />";
}
function function8($test_arg){	
	sleep(8);
	return "8th passing var ".$test_arg.".<br />";
}
function function9($test_arg){	
	sleep(2);
	return "9th passing var ".$test_arg.".<br />";
}
function function10($test_arg){	
	sleep(4);
	return "10th passing var ".$test_arg.".<br />";
}

$program_start_time = time();

/*$thread_1 = new Thread("173.201.20.12",80);
$thread_1->setFunc("function1",array("Var 1"));

$thread_2 = new Thread("173.201.20.12",80);
$thread_2->setFunc("function2",array("Var 2"));

$thread_3 = new Thread("173.201.20.12",80);
$thread_3->setFunc("function3",array("Var 3"));

$thread_4 = new Thread("173.201.20.12",80);
$thread_4->setFunc("function4",array("Var 4"));

$thread_5 = new Thread("173.201.20.12",80);
$thread_5->setFunc("function5",array("Var 5"));

$thread_6 = new Thread("173.201.20.12",80);
$thread_6->setFunc("function6",array("Var 6"));

$thread_7 = new Thread("173.201.20.12",80);
$thread_7->setFunc("function7",array("Var 7"));

$thread_8 = new Thread("173.201.20.12",80);
$thread_8->setFunc("function8",array("Var 8"));

$thread_9 = new Thread("173.201.20.12",80);
$thread_9->setFunc("function9",array("Var 9"));

$thread_10 = new Thread("173.201.20.12",80);
$thread_10->setFunc("function10",array("Var 10"));*/

for($i=1; $i<=5; $i++)
{	
	$thread_value = "thread_".$i;
	$$thread_value = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);
	$$thread_value->setFunc("function$i",array("Var $i","7987897"),"89789");
	$$thread_value->start();
	//echo $$thread_array->getreturn();
}
for($i=1; $i<=5; $i++)
{
	$thread_value = "thread_".$i;
	echo $$thread_value->getreturn();
}

/*$thread_1->start();
$thread_2->start();
$thread_3->start();
$thread_4->start();
$thread_5->start();
$thread_6->start();
$thread_7->start();
$thread_8->start();
$thread_9->start();
$thread_10->start();*/

/*echo $thread_1->getreturn();
echo $thread_2->getreturn();
echo $thread_3->getreturn();
echo $thread_4->getreturn();
echo $thread_5->getreturn();
echo $thread_6->getreturn();
echo $thread_7->getreturn();
echo $thread_8->getreturn();
echo $thread_9->getreturn();
echo $thread_10->getreturn();*/


echo "<br>Main Program has run ".(time()-$program_start_time)." seconds<br />";



?>