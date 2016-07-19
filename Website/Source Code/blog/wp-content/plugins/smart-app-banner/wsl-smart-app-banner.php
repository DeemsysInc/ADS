<?php
/**
 * @package WSLSmartAppBanner
 * @version 1.1.0
 */
/*
Plugin Name: Smart App Banner
Plugin URI: http://www.wandlesoftware.com/products/open-source-software/wordpress-smart-app-banner-plugin
Description: Makes the Smart App Banner appear on iOS6 and above. 
Author: Stephen Darlington, Wandle Software Limited
Text Domain: wsl-smart-app-banner
Version: 1.1.0
Author URI: http://www.wandlesoftware.com/
License: GPL
*/

/*  Copyright 2012-2014 Stephen Darlington, Wandle Software Limited

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function wsl_smart_app_banner_init() {
 $plugin_dir = basename(dirname(__FILE__));
 load_plugin_textdomain( 'wsl-smart-app-banner', false, $plugin_dir );
}
add_action('plugins_loaded', 'wsl_smart_app_banner_init');

function wsl_output_safari_app_banner($post_ID) {
  // This is a weird order, but the idea is that if there's a local
  // definition we use that in preference to the global option
  if (! is_front_page()) {
    // check for properties that give us the app id
    $custom_fields = get_post_custom($post_ID);
    $app_id_list = $custom_fields['_wsl-app-id'];

    $app_id_ipad_list = $custom_fields['_wsl-app-id-ipad'];
    $affiliate_data_list = $custom_fields['_wsl-affiliate-data'];
    $app_argument_list = $custom_fields['_wsl-app-argument'];

    $app_id = $app_id_list[0];
    $app_id_ipad = $app_id_ipad_list[0];
    $affiliate_data = $affiliate_data_list[0];
    $app_argument = $app_argument_list[0];
  }

  if ((is_null($app_id_list) or $app_id == "") and
      (is_front_page() or get_option('wsl_global_banner') == 'Yes')) {
    $app_id = get_option('wsl_homepage_appid');
    $app_id_ipad = get_option('wsl_homepage_appid_ipad');
    $affiliate_data = get_option('wsl_homepage_affiliate');
    $app_argument = get_option('wsl_homepage_argument');
  }

  // if it's not there, exit
  if (is_null($app_id) or $app_id == "") {
    return;
  }

  $options = "";

  if (! is_null($affiliate_data) and $affiliate_data != "") {
    $options = "$options, affiliate-data=$affiliate_data";
  }
  if (! is_null($app_argument) and $app_argument != "") {
    $options = "$options, app-argument=$app_argument";
  }

  // if it is, output the header
  if (is_null($app_id_ipad) or $app_id_ipad == "") {
    echo "<meta name=\"apple-itunes-app\" content=\"app-id=$app_id$options\">";
  }
  else {
    ?>
<script language="javascript">
<!--
if (navigator.userAgent.match(/iPad/i) != null) {
document.write("<meta name=\"apple-itunes-app\" content=\"app-id=<?php echo "$app_id_ipad$options"; ?>\">\n");
}
else {
document.write("<meta name=\"apple-itunes-app\" content=\"app-id=<?php echo "$app_id$options"; ?>\">");
}
// -->
</script>
    <?php
  }
}

add_action( 'wp_head', 'wsl_output_safari_app_banner' );

// Admin menu gubbins
function wsl_smart_app_banner_admin_menu() {
  add_options_page( __('Smart App Banner Settings', 'wsl-smart-app-banner'),
                    __('Smart App Banner', 'wsl-smart-app-banner'),
                    'manage_options',
                    'wsl-smart-app-banner',
                    'wsl_smart_app_banner_options' );
}

function wsl_smart_app_banner_options() {
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $hidden_field_name = 'wsl_submit_hidden';

    $appid_field_name = 'wsl_homepage_appid';
    $appid_ipad_field_name = 'wsl_homepage_appid_ipad';
    $affiliate_field_name = 'wsl_homepage_affiliate';
    $argument_field_name = 'wsl_homepage_argument';
    $global_banner_field_name = 'wsl_global_banner';
    $app_list_field_name = 'wsl_app_list';
    
    $new_app_name_field = 'app_name';
    $new_app_id_field = 'app_id';
    $new_app_id_ipad_field = 'app_id_ipad';
    $new_app_affiliate_field = 'app_affiliate';
    $new_app_argument_field = 'app_argument';
    
    // Read in existing option value from database
    $appid_val = get_option( $appid_field_name );
    $appid_ipad_val = get_option( $appid_ipad_field_name );
    $affiliate_val = get_option( $affiliate_field_name );
    $argument_val = get_option( $argument_field_name );
    $global_banner_val = get_option( $global_banner_field_name );
    
    $app_list = get_option ( $app_list_field_name, array() );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
    
      if (isset($_POST['add'])) {
        // add new app
        $app_name = $_POST[$new_app_name_field];
        $app_id = $_POST[$new_app_id_field];
        $app_id_ipad = $_POST[$new_app_id_ipad_field];
        $app_affiliate_data = $_POST[$new_app_affiliate_field];
        $app_argument_data = $_POST[$new_app_argument_field];
        
        if (isset($app_id) and $app_id != "") {
          $app_list[$app_id] = array (
                    'app_name' => $app_name,
                    'appid_ipad' => $app_id_ipad,
                    'affiliate_data' => $app_affiliate_data,
                    'app_argument' => $app_argument_data,
                );
          update_option ($app_list_field_name, $app_list);
      
          // Put an settings updated message on the screen
?>
<div class="updated"><p><strong><?php  _e( 'app added.', 'wsl-smart-app-banner' ); ?></strong></p></div>
<?php
        }

      }
      elseif (isset($_POST['changeHome'])) {

        // Read their posted value
        $appid_val = $_POST[ $appid_field_name ];
        $appid_ipad_val = $_POST[ $appid_ipad_field_name ];
        $affiliate_val = $_POST[ $affiliate_field_name ];
        $argument_val = $_POST[ $argument_field_name ];
        $global_banner_val = $_POST[ $global_banner_field_name ];

        // Save the posted value in the database
        update_option( $appid_field_name, $appid_val );
        update_option( $appid_ipad_field_name, $appid_ipad_val );
        update_option( $affiliate_field_name, $affiliate_val );
        update_option( $argument_field_name, $argument_val );
        if( $_POST[ $global_banner_field_name ] == "Yes") {
          update_option( $global_banner_field_name, "Yes");
        }
        else {
          update_option( $global_banner_field_name, "No");
        }

        // Put an settings updated message on the screen

?>
<div class="updated"><p><strong><?php _e('settings saved.', 'wsl-smart-app-banner' ); ?></strong></p></div>
<?php
      }
    else { // delete

	foreach ($_POST as $k => $v) {
	  if (preg_match('/^delete_/', $k) === 1) {
	    $to_delete = substr($k, 7);
	    unset($app_list[$to_delete]);
	  }
	}
	update_option ($app_list_field_name, $app_list);

?>
	  <div class="updated"><p><strong><?php _e('deleted ', 'wsl-smart-app-banner' ); ?></strong></p></div>
<?php
      }
    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Smart App Banner Settings', 'wsl-smart-app-banner' ) . "</h2>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<h3><?php _e('Homepage', 'wsl-smart-app-banner'); ?></h3>
<p><?php _e('These values are used on your home page. (Leave blank if no banner is required.)', 'wsl-smart-app-banner'); ?></p>

<table>
  <tr>
    <td><?php _e('App ID:','wsl-smart-app-banner'); ?></td>
    <td><input type="text" name="<?php echo $appid_field_name; ?>" value="<?php echo $appid_val; ?>" /></td>
  </tr>

  <tr>
    <td><?php _e('App ID (iPad):','wsl-smart-app-banner'); ?></td>
    <td><input type="text" name="<?php echo $appid_ipad_field_name; ?>" value="<?php echo $appid_ipad_val; ?>" /> <?php _e('(optional)', 'wsl-smart-app-banner'); ?></td>
  </tr>

  <tr>
    <td><?php _e('Affiliate data:','wsl-smart-app-banner'); ?></td>
    <td><input type="text" name="<?php echo $affiliate_field_name; ?>" value="<?php echo $affiliate_val; ?>" /></td>
  </tr>

  <tr>
    <td><?php _e('App argument:','wsl-smart-app-banner'); ?></td>
    <td><input type="text" name="<?php echo $argument_field_name; ?>" value="<?php echo $argument_val; ?>" /></td>
  </tr>

  <tr>
    <td><?php _e('Show on all pages:','wsl-smart-app-banner'); ?></td>
    <td><input type="checkbox" name="<?php echo $global_banner_field_name; ?>" value="Yes" <?php if ($global_banner_val == "Yes") { echo "checked"; } ?> /></td>
  </tr>

</table>


<p class="submit">
<input type="submit" name="changeHome" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

<h3><?php _e('Apps', 'wsl-smart-app-banner'); ?></h3>

<table>
  <tr>
    <td>&nbsp;</td>
    <td><h4><?php _e('App name','wsl-smart-app-banner'); ?></h4></td>
    <td><h4><?php _e('App ID','wsl-smart-app-banner'); ?></h4></td>
    <td><h4><?php _e('App ID (iPad):','wsl-smart-app-banner'); ?></h4></td>
    <td><h4><?php _e('Affiliate data:','wsl-smart-app-banner'); ?></h4></td>
    <td><h4><?php _e('App argument:','wsl-smart-app-banner'); ?></h4></td>
    <td>&nbsp;</td>
  </tr>

<?php

  foreach ($app_list as $appid => $app) {

?>

  <tr>
    <td><input type="checkbox" name="delete_<?php echo $appid; ?>" value="<?php echo $app['app_name']; ?>" /></td>
    <td><?php echo $app['app_name']; ?></td>
    <td><?php echo $appid; ?></td>
    <td><?php echo $app['appid_ipad']; ?></td>
    <td><?php echo $app['affiliate_data']; ?></td>
    <td><?php echo $app['app_argument']; ?></td>
  </tr>

<?php

  }

?>

  <tr>
    <td>&nbsp;</td>
    <td><input type="text" name="<?php echo $new_app_name_field; ?>" /></td>
    <td><input type="text" name="<?php echo $new_app_id_field;?>" /></td>
    <td><input type="text" name="<?php echo $new_app_id_ipad_field; ?>" /></td>
    <td><input type="text" name="<?php echo $new_app_affiliate_field; ?>" /></td>
    <td><input type="text" name="<?php echo $new_app_argument_field; ?>" /></td>
    <td>&nbsp;</td>
  </tr>

</table>


<p class="submit">
<input type="submit" name="delete" class="button-secondary" value="<?php esc_attr_e('Delete App') ?>" />
<input type="submit" name="add" class="button-primary" value="<?php esc_attr_e('Add new App') ?>" />
</p>

</form>

</div>

<?php
}

add_action( 'admin_menu', 'wsl_smart_app_banner_admin_menu' );

// register the meta box
add_action( 'add_meta_boxes', 'wsl_smart_app_banner_post_options' );
function wsl_smart_app_banner_post_options() {
    foreach (get_post_types() as $element) {
      add_meta_box(
          'wsl_smart_app_banner_id',          // this is HTML id of the box on edit screen
          __('Smart App Banner','wsl-smart-app-banner'),    // title of the box
          'wsl_smart_app_banner_display_options',   // function to be called to display the checkboxes, see the function below
          $element,        // on which edit screen the box should appear
          'normal',      // part of page where the box should appear
          'default'      // priority of the box
      );
    }
}

// display the metabox
function wsl_smart_app_banner_display_options( $post_id ) {
    // nonce field for security check, you can have the same
    // nonce field for all your meta boxes of same plugin
    wp_nonce_field( plugin_basename( __FILE__ ), 'wsl-sab-nonce' );

    $custom_fields = get_post_custom($post_ID);
    $app_id_list = $custom_fields['_wsl-app-id'];
    $app_id_ipad_list = $custom_fields['_wsl-app-id-ipad'];
    $affiliate_data = $custom_fields['_wsl-affiliate-data'];
    $app_argument = $custom_fields['_wsl-app-argument'];
    
    $app_list = get_option ( 'wsl_app_list', array() );

    ?>
    <table>
    <?php
      if (count($app_list) > 0) {
    ?>
    <script>
      function updateAppBannerTable(idx) {
        if (idx == 0) {
          return;
        }

        document.getElementById('app_id').value = [ <?php foreach ($app_list as $id => $data) echo "'$id', " ?> ][idx-1];
        document.getElementById('app_id_ipad').value = [ <?php foreach ($app_list as $id => $data) echo "'", $data['appid_ipad'], "', " ?> ][idx-1];
        document.getElementById('app_affiliate').value = [ <?php foreach ($app_list as $id => $data) echo "'", $data['affiliate_data'], "', " ?> ][idx-1];
        document.getElementById('app_argument').value = [ <?php foreach ($app_list as $id => $data) echo "'", $data['app_argument'], "', " ?> ][idx-1];
      }
    </script>
      <tr>
        <td><?php _e('Apps:','wsl-smart-app-banner'); ?></td>
        <td><select onClick="if (typeof(this.selectedIndex) != 'undefined') { updateAppBannerTable(this.selectedIndex); }"><option></option>
        <?php
        foreach ($app_list as $appid => $appdata) {
          echo '<option>', $appdata['app_name'], '</option>';
        }
        ?>
        </select></td>
      </tr>
    <?php
      }
    ?>
      <tr>
        <td><?php _e('App ID:','wsl-smart-app-banner'); ?></td>
        <td><input id="app_id" type="text" name="wsl_smart_app_banner_app_id" value="<?php echo $app_id_list[0]; ?>" /></td>
      </tr>
      <tr>
        <td><?php _e('App ID (iPad):','wsl-smart-app-banner'); ?></td>
        <td><input id="app_id_ipad" type="text" name="wsl_smart_app_banner_app_id_ipad" value="<?php echo $app_id_ipad_list[0]; ?>" /> <?php _e('(optional)', 'wsl-smart-app-banner'); ?></td>
      </tr>
      <tr>
        <td><?php _e('Affiliate data:','wsl-smart-app-banner'); ?></td>
        <td><input id="app_affiliate" type="text" name="wsl_smart_app_banner_affiliate_data" value="<?php echo $affiliate_data[0]; ?>" /></td>
      </tr>
      <tr>
        <td><?php _e('App argument:','wsl-smart-app-banner'); ?></td>
        <td><input id="app_argument" type="text" name="wsl_smart_app_banner_app_argument" value="<?php echo $app_argument[0]; ?>" /></td>
      </tr>
    </table>
    <?php
}

// save data from checkboxes
add_action( 'save_post', 'wsl_smart_app_banner_app_save' );
function wsl_smart_app_banner_app_save($post_ID) {

    // check if this isn't an auto save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    // security check
    if ( !wp_verify_nonce( $_POST['wsl-sab-nonce'], plugin_basename( __FILE__ ) ) )
        return;

    // further checks if you like, 
    // for example particular user, role or maybe post type in case of custom post types

    // now store data in custom fields based on checkboxes selected
    if ( isset( $_POST['wsl_smart_app_banner_app_id'] ) ) {
      
      add_post_meta($post_ID, '_wsl-app-id', $_POST['wsl_smart_app_banner_app_id'] , true) or
          update_post_meta($post_ID, '_wsl-app-id', $_POST['wsl_smart_app_banner_app_id']);
    }
    if ( isset( $_POST['wsl_smart_app_banner_app_id_ipad'] ) ) {
      
      add_post_meta($post_ID, '_wsl-app-id-ipad', $_POST['wsl_smart_app_banner_app_id_ipad'] , true) or
          update_post_meta($post_ID, '_wsl-app-id-ipad', $_POST['wsl_smart_app_banner_app_id_ipad']);
    }
    if ( isset( $_POST['wsl_smart_app_banner_affiliate_data'] ) ) {
      
      add_post_meta($post_ID, '_wsl-affiliate-data', $_POST['wsl_smart_app_banner_affiliate_data'] , true) or
          update_post_meta($post_ID, '_wsl-affiliate-data', $_POST['wsl_smart_app_banner_affiliate_data']);
    }
    if ( isset( $_POST['wsl_smart_app_banner_app_argument'] ) ) {
      
      add_post_meta($post_ID, '_wsl-app-argument', $_POST['wsl_smart_app_banner_app_argument'] , true) or
          update_post_meta($post_ID, '_wsl-app-argument', $_POST['wsl_smart_app_banner_app_argument']);
    }
}

// let's uninstall ourselves cleanly
register_uninstall_hook(__FILE__ , 'wsl_smart_app_banner_uninstall');
function wsl_smart_app_banner_uninstall() {
    delete_option('wsl_global_banner');
    delete_option('wsl_homepage_appid');
    delete_option('wsl_homepage_appid_ipad');
    delete_option('wsl_homepage_affiliate');
    delete_option('wsl_homepage_argument');
}

?>
