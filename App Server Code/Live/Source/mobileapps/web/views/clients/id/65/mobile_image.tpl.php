


<?php 

$imgUrl=isset($_REQUEST['url']) ? $_REQUEST['url'] : '';

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- <meta name="viewport" content="width=device-width, initial-scale=0, maximum-scale=100, user-scalable=1"/> -->
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=100, user-scalable=1" />
<title>Image </title>
<style type="text/css">
#imagediv img{
    max-width:100% !important;
    height:auto;
    display:block;
}

</style>

</head>

<body>
	<div id="imagediv"><img src="/files/clients/65/additional/<?php echo $imgUrl;?>" alt="Sample"  /></div>

</body>
</html>
