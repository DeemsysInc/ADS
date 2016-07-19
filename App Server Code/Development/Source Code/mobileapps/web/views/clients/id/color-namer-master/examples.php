<?php
    /**
    * ColorNamer 1.0
    * Copyright 2011 Tufan Baris YILDIRIM
    *
    * Website: http://me.tufyta.com
    *
    * $Id: example.php 2011-07-14 04:51:02Z tfnyldrm $
    */

    /**
    * Including ColorNamer first.
    */
    include 'ColorName.php';

    /**
    * Preparing class, load the .dat file first.
    */
    ColorName::Prepare('colors.dat');

    #analizing..

    #random hex value, 00 - FF
    function rh()
    {
        return strtoupper(str_pad(dechex(rand(0,255)),2,0));
    }

    #generate random color code.
    function randomColor()
    {
		return "#5EFB6E";
        //return '#' . rh() . rh() . rh();
    }
	$hashCode = isset($_REQUEST['hashcode']) ? $_REQUEST['hashcode'] : "#701C1C";
	$colorInfo = ColorName::GetInfo($hashCode);
	//echo $colorInfo->name $colorInfo->code;
	$arrResult =  array();
	for($i = 0;$i < 10; $i ++):
	
	$arrResult[$i]['name'] = $colorInfo->name;
	$arrResult[$i]['code'] = $colorInfo->code;
	$arrResult[$i]['similarity'] = "%".$colorInfo->similarity;
	endfor;
	
	//$arrResult['name'] = isset($_REQUEST['hashcode']) ? $_REQUEST['hashcode'] : "#5EFB6E";
	
	echo  json_encode($arrResult);
	//echo $colorInfo->name;
	//echo "test";
	
?>
