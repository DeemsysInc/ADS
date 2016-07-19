<?php
//ini_set('display_errors', '1');
define('ga_email','didi4team');
define('ga_password','didi@123');
define('ga_profile_id','77150719');


require 'gapi.class.php';

$ga = new gapi(ga_email,ga_password);

$ga->requestReportData(ga_profile_id,array('mobileDeviceBranding','mobileDeviceInfo'),array('screenviews','uniqueScreenviews'), $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30);


//echo "sdfsdfd";
//exit;

print_r($ga->getResults());


?>
<table>
<tr>
  <th>Browser &amp; Browser Version</th>
  <th>Pageviews</th>
  <th>Visits</th>
</tr>
<?php
foreach($ga->getResults() as $result):
echo $result;
?>
<tr>
  <td><?php echo $result ?></td>
  <td><?php echo $result->getScreenViews() ?></td>
  <td><?php echo $result->getuniqueScreenviews() ?></td>
</tr>
<?php
endforeach
?>
</table>

