<?php

include_once("Thread.php");

function test($test_arg){

	return "Pass in variable ".$test_arg.".<br />";

}

function test_2($test_arg){

	$start = time();

	while (time() < $start+$test_arg){

		

	}

	return $test_arg." seconds have passed.<br />";

}



$program_start_time = time();



$thread_a = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);

$thread_a->setFunc("test",array("Hello World"));

$thread_a->start();



$thread_b = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);

$thread_b->setFunc("test_2",array(2));

$thread_b->start();



$thread_c = new Thread($_SERVER ['SERVER_NAME'], $_SERVER['SERVER_PORT']);

$thread_c->setFunc("test_2",array(1));

$thread_c->start();



echo $thread_a->getreturn();

echo $thread_b->getreturn();

echo $thread_c->getreturn();



echo "Main Program has run ".(time()-$program_start_time)." seconds<br />";



?>