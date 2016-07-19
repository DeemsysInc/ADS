<html>
<head>
<link href="bands.css" rel="stylesheet">
<style type="text/css">
u {    
    border-bottom:  2px dotted #000;
    text-decoration: none;
}
</style>
</head>
<body>
<div >
<h3 class="heading"><u>Venue : </u></h3>
</div>
<p>Columbus Commons<br>
160 S High Street<br>
Columbus, OH</p>
<div>
<h3 class="heading"><u>Timings : </u></h3>
</div>
<p><?php echo isset($_REQUEST['w']) ?strtoupper($_REQUEST['w']) : "";?><br>
<?php 
$timingsArray = isset($_REQUEST['t']) ? explode("?",$_REQUEST['t']):"";

 echo  strtoupper(str_replace("%20"," ",$timingsArray[0])); ?></p>

</body>
</html>

 
