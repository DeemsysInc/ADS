<?php
for ($i=0; $i < 120; $i++) { 
	$prodId = (7177+$i);
 	echo "INSERT INTO `devarapp_cms`.`products_background_view` (`pd_bg_id`, `pd_id`, `pd_bg_color`, `pd_hide_bg_image`, `pd_bg_status`) VALUES (NULL, '$prodId', '', '0', '1');";
}
?>