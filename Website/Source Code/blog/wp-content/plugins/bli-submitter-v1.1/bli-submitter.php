<?php
/*
Plugin Name: BLI Submitter
Plugin URI: http://backlinksindexer.com/members-dashboard/api/
Description: This is the plugin which works with the built-in API of backlinksindexer.com in order to submit URLS.
Version: 1.1
Author: BacklinksIndexer.com
Author URI: http://www.backlinksindexer.com
License: A "Enterprise" license name e.g. GPL2
*/

if (!defined('BLI_SUBMITTER_THEME_DIR'))
    define('BLI_SUBMITTER_THEME_DIR', ABSPATH . 'wp-content/themes/' . get_template());

if (!defined('BLI_SUBMITTER_PLUGIN_NAME'))
    define('BLI_SUBMITTER_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('BLI_SUBMITTER_PLUGIN_DIR'))
    define('BLI_SUBMITTER_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . BLI_SUBMITTER_PLUGIN_NAME);

if (!defined('BLI_SUBMITTER_PLUGIN_URL'))
    define('BLI_SUBMITTER_PLUGIN_URL', WP_PLUGIN_URL . '/' . BLI_SUBMITTER_PLUGIN_NAME);

if (!defined('BLI_SUBMITTER_PLUGIN_VIEW_URL'))
    define('BLI_SUBMITTER_PLUGIN_VIEW_URL', WP_PLUGIN_URL . '/' . BLI_SUBMITTER_PLUGIN_NAME. '/views');

if (!defined('BLI_SUBMITTER_PLUGIN_VIEW_DIR'))
    define('BLI_SUBMITTER_PLUGIN_VIEW_DIR', WP_PLUGIN_DIR . '/' . BLI_SUBMITTER_PLUGIN_NAME. '/views');

if (!defined('BLI_SUBMITTER_PLUGIN_DEBUG'))
    define('BLI_SUBMITTER_PLUGIN_DEBUG', true);

if (!defined('BACKLINK_API_URL'))
    define('BACKLINK_API_URL', 'http://backlinksindexer.com/api.php');

require_once "include/db.php";

require_once "include/classes/utils.php";
require_once "include/classes/generic-model.php";
require_once "include/classes/submitter.php";
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

require_once "include/classes/settings.php";
require_once "include/classes/post.php";
require_once "include/classes/posts-sent-to-bli.php";


register_activation_hook( __FILE__, array( 'BliSubmitter', 'install' ) );
register_deactivation_hook(__FILE__, array('BliSubmitter', 'uninstall') );

$SubmitterObj = new BliSubmitter();

function bli_submitter_style() {	
    wp_enqueue_style( 'bli-submitter-style', BLI_SUBMITTER_PLUGIN_VIEW_URL.'/style.css' );
    wp_enqueue_style( 'bli-submitter-datepicker-style', BLI_SUBMITTER_PLUGIN_VIEW_URL.'/datepicker/css/ui-lightness/jquery-ui-1.10.3.custom.min.css' );
}

function bli_submitter_scripts() {	
	wp_register_script('bli-submitter-datepicker-script', BLI_SUBMITTER_PLUGIN_VIEW_URL.'/datepicker/js/jquery-ui-1.10.3.custom.min.js');
	wp_enqueue_script('bli-submitter-datepicker-script');
  
}

add_action('admin_print_styles', 'bli_submitter_style');
add_action('admin_print_scripts', 'bli_submitter_scripts');