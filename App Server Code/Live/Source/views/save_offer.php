
<style type="text/css">

.classname {
	-moz-box-shadow:inset 0px 1px 0px 0px #f5978e;
	-webkit-box-shadow:inset 0px 1px 0px 0px #f5978e;
	box-shadow:inset 0px 1px 0px 0px #f5978e;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f24537), color-stop(1, #c62d1f) );
	background:-moz-linear-gradient( center top, #f24537 5%, #c62d1f 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#f24537', endColorstr='#c62d1f');
	background-color:#f24537;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #d02718;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:30px;
	font-weight:bold;
	font-style:normal;
	height:50px;
	line-height:50px;
	width:200px;
	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px #810e05;
}
.classname:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #c62d1f), color-stop(1, #f24537) );
	background:-moz-linear-gradient( center top, #c62d1f 5%, #f24537 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c62d1f', endColorstr='#f24537');
	background-color:#c62d1f;
}.classname:active {
	position:relative;
	top:1px;
}
</style>
<?php 
	$imgURL = isset($_REQUEST['img']) ? $_REQUEST['img'] : '';
	echo 'image url: '.$imgURL = "http://" . $_SERVER['SERVER_NAME']."/files/clients/".$imgURL;
	$callNow = isset($_REQUEST['call']) ? $_REQUEST['call'] : '';
	$callNow = "tel:".$callNow;
	echo 'pid: '.$pid = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : 0;
	echo 'oid: '.$oid = isset($_REQUEST['oid']) ? $_REQUEST['oid'] : 0;
	echo 'uid: '.$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : 0;
	echo 'offerid: '.$offerid = isset($_REQUEST['offerid']) ? $_REQUEST['offerid'] : 0;

	
?>
<div align="center">
<br />
<img src="<?php echo $imgURL; ?>">
<br /><br />
<a  class="classname" href="javascript:;">Save Offer</a>
<br /><br /><br /><br />
</div>
