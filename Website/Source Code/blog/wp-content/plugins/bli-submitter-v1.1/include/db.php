<?php
// Changes the version number, if you are changing anything in this file
if (!defined('BLI_SUBMITTER_VERSION_KEY'))
    define('BLI_SUBMITTER_VERSION_KEY', 'bli_submitter_version');

if (!defined('BLI_SUBMITTER_VERSION_NUM'))
    define('BLI_SUBMITTER_VERSION_NUM', '1');

if (get_option(BLI_SUBMITTER_VERSION_KEY) != BLI_SUBMITTER_VERSION_NUM) {
    // This will run when the application find a new version OR mis-match
	bli_submitter_db_upgrade();
}

function bli_submitter_db_upgrade(){
	bli_submitter_db_install();
	update_option(BLI_SUBMITTER_VERSION_KEY, BLI_SUBMITTER_VERSION_NUM);
}

function bli_submitter_db_install(){
	add_option(BLI_SUBMITTER_VERSION_KEY, BLI_SUBMITTER_VERSION_NUM);
	$sql = bli_submitter_db_sqls("install.sql");
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	//echo $sql;
	dbDelta($sql);
	
}

function bli_submitter_db_uninstall(){
	global $wpdb;
	$sqls = bli_submitter_db_sqls("uninstall.sql");
	$sql_array = explode("\n",$sqls);
	if(is_array($sql_array) && count($sql_array) > 0){
		foreach($sql_array as $sql){
			$sql = trim($sql);
			if($sql != "")
				$wpdb->query($sql);
		}
	}
}

function bli_submitter_db_sqls($file){
	global $wpdb;
	$sql = file_get_contents(dirname ( __FILE__ ).DIRECTORY_SEPARATOR."sql".DIRECTORY_SEPARATOR.$file);
	$table_prefix = $wpdb->prefix;
	return str_replace("%%WP-PREFIX%%", $table_prefix, $sql);
}
