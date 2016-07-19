<html>
<head>
<link href="../../js/jquery/jquery-ui.css" rel="stylesheet">
<style type="text/css">
u {    
    border-bottom:  2px dotted #000;
    text-decoration: none;
}
</style>
</head>
<body>
<div class="ui-widget">
<p><h3><u>Venue : </u></h3></p>
</div>
<p>Columbus Commons<br>
160 S High Street<br>
Columbus, OH</p>
<div class="ui-widget">
<p><h3><u>Timings : </u></h3></p>
<p><?php echo isset($_REQUEST['w']) ?strtoupper($_REQUEST['w']) : "";?><br>
<?php echo isset($_REQUEST['t']) ? strtoupper(str_replace("%20"," ",$_REQUEST['t'])) : "";?></p>
</div>
</body>
</html>

 
