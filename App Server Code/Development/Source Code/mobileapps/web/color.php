<?php 
global $config;

require_once 'smcfg_new.php';

global $getConfig;

require_once $srvRoot.'classes/config.class.php';
$objConfig = new cConfig();
$getConfig = $objConfig->config();
$sqlConnect = mysql_connect($config['database']['host'],$config['database']['user'],$config['database']['password'],$config['database']['name']);
if($sqlConnect){
echo "mysql connected";
mysql_select_db($config['database']['name']);
}else{
echo "unable to connect mysql";
}

//function to convert hex value to rgb
function hex2rgb($hex)
{
    return array(
            hexdec(substr($hex,1,2)),
            hexdec(substr($hex,3,2)),
            hexdec(substr($hex,5,2))
        );
}

echo $colorVal =isset($_REQUEST['color'])?$_REQUEST['color']:'';

$color = hex2rgb($colorVal);//return an rgb array

//calculate the distance between two colors
function dist($col1,$col2) {
  $delta_r = $col1[0] - $col2[0];
  $delta_g = $col1[1] - $col2[1];
  $delta_b = $col1[2] - $col2[2];
  return $delta_r * $delta_r + $delta_g *$delta_g + $delta_b * $delta_b;
} 


 $color_from = str_pad(dechex(($color[0] << 16) + ($color[1] << 8) + $color[2]), 6, 0, STR_PAD_LEFT);

//retreive main categories values

$color_categories = mysql_query("SELECT * FROM `color_categories`");
if($color_categories)
{
    while($color_categories_results = mysql_fetch_assoc($color_categories))
    {
        $color_categories_main[]= $color_categories_results;
    }
	//print_r($color_categories_main);
} 

//retreive sub categories values
$color_sub_categories = mysql_query("SELECT * FROM  `color_sub_categories`");
if($color_sub_categories)
{
    while($color_sub_categories_results = mysql_fetch_assoc($color_sub_categories))
    {
        $color_sub_categories_res[]= $color_sub_categories_results;
    }
	//print_r($color_sub_categories_res);
} 

//creating rgb value as an array
$rgb_value_sub_categories = explode(",",$color_sub_categories_res[0]['color_sub_cat_rgb']);

//assiging first sub category rgb value as basic
$closest = $rgb_value_sub_categories;

//getting the distance between two colors
$mindist=dist($color,$rgb_value_sub_categories);

$ncolors=sizeof($color_sub_categories_res);
for($i = 0; $i < $ncolors; ++$i)
{
	$rgb_sub_categories = explode(",",$color_sub_categories_res[$i]['color_sub_cat_rgb']);
    $currdist = dist($color,$rgb_sub_categories);
    if($currdist<$mindist) {
      $mindist=$currdist;
      $closest=$color_sub_categories_res[$i];
    }
}
for($i = 0; $i < count($color_categories_main); ++$i)
{	
    if($color_categories_main[$i]['color_cat_id'] == $closest['color_cat_id']) {	
     $mclosest = $color_categories_main[$i];
    }
}
//displaying results
$sub_rgb_result = explode(",",$closest['color_sub_cat_rgb']);
$sub_category_value=str_pad(dechex(($sub_rgb_result[0] << 16) + ($sub_rgb_result[1] << 8) + $sub_rgb_result[2]), 6, 0, STR_PAD_LEFT);
$main_rgb_result = explode(",",$mclosest['color_rgb']);
$main_category_value=str_pad(dechex(($main_rgb_result[0] << 16) + ($main_rgb_result[1] << 8) + $main_rgb_result[2]), 6, 0, STR_PAD_LEFT);

?>


<html>
<form name="input" action="color.php" method="get">
Color code: <input type="text" name="color">
<input type="submit" value="Submit">
</form>
</html><?php echo
    'Color From <div style="background-color: #' . $color_from . '; width: 25px; height: 25px;"></div>' .$color_from."<br>".
	'Sub Category Color:'.$closest['color_sub_cat_name'].' <div style="background-color: #' . $sub_category_value . '; width: 25px; height: 25px;"></div>' .
    '<br>Main Match Color:'.$mclosest['color_name'].' <div style="background-color: #' . $main_category_value   . '; width: 25px; height: 25px;"></div>';
?>

