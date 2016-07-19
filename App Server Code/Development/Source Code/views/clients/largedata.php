<?php 

include_once("thread.php"); 

function test(){ 

    return "Pass in variable "; 

} 

function test_2($test_arg){ 

    $start = time(); 

    while (time() < $start+$test_arg){ 

         

    } 

    return $test_arg." seconds have passed.<br />"; 

} 



$program_start_time = time(); 



$thread_a = new Thread("50.63.174.132",80); 

$thread_a->setFunc("test"); 

$thread_a->start(); 



$thread_b = new Thread("50.63.174.132",80); 

$thread_b->setFunc("test_2",array(2)); 

$thread_b->start(); 



$thread_c = new Thread("50.63.174.132",80); 

$thread_c->setFunc("test_2",array(1)); 

$thread_c->start(); 



echo $thread_a->getreturn(); 

echo $thread_b->getreturn(); 

echo $thread_c->getreturn(); 



echo "Main Program has run ".(time()-$program_start_time)." seconds<br />"; 



?>