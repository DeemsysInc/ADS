<h1 style="color:white;">redirect page: <?php echo $_REQUEST['q'];?></h1>
<?php 
/*** Display errors at runtime ***/
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once 'smcfg_mobile.php';

global $getConfig;

require_once $srvRoot.'classes/config.class.php';
$objConfig = new cConfig();
$getConfig = $objConfig->config();

/*** include MVC Architecture ***/
require_once $getConfig['ABSOLUTEPATH'].'mvc.php?q';

?>