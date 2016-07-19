<?php
class C {
	var $bad_1 = 1e-6;	// Bug in unserialize
	var $bad_2 = 1.0e-6;	// Bug in unserialize
	var $ok__1 = 1.1e-6;
	var $ok__2 = 9e-7;
}

$c=new C();
echo $s=serialize($c);
echo $cc=unserialize($s);
?> 