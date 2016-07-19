<?php
//=============================================
// Create 'Follow Me' Widget
//=============================================
class HubSpot_Social_Widget extends WP_Widget {
    
    /** constructor */
    function HubSpot_Social_Widget() {
        $this->WP_Widget(false, $name = 'HubSpot: Follow Widget');  
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) { 
        extract( $args );
        $hs_settings = array();
        $hs_settings = get_option('hs_settings');
        //$hs_settings["hs_appdomain"];
        //$hs_settings["hs_portal"];
        $title = __('Follow Me');
        ?>
              <?php echo $before_widget; ?>
                  <?php if ($title){
                        echo $before_title . $title . $after_title; 
                  } ?>
                      <!-- hubspot.com follow code -->
                      <div class="hs_followme" style="height:52px"></div>
                      <script type="text/javascript">
                      var __hs_fm = {portal: <?php echo $hs_settings["hs_portal"]; ?>, host: 'hubapi.com', blog: false};
                      (function(){
                          var fm = document.createElement('script');
                          fm.type = 'text/javascript'; fm.async = true;
                          fm.src = '//static.hubspot.com/js/fm.js';
                          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(fm, s);
                      })();
                      </script>
                      <!-- end hubspot.com follow code -->
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
                global $myhubspotusage;
                $myhubspotusage->check_widget('Add/Update Follow Widget');
        $instance = $old_instance;
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $hs_settings = array();
        $hs_settings = get_option('hs_settings');
        ?>
            <p>Edit widget settings <a href="https://app.hubspot.com/social/<?php echo $hs_settings["hs_portal"]; ?>/settings">here</a></p>
        <?php
    }

} // class HubSpot_Social_Widget 

//=============================================
// Create 'Call to Action' Widget
//=============================================
class HubSpot_Action_Widget extends WP_Widget {
    
    /** constructor */
    function HubSpot_Action_Widget() {
        $this->WP_Widget(false, $name = 'HubSpot: Action Widget');  
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) { 
        global $myhubspotwp_action;
        extract( $args );
        $hide_title = $instance['hide_title'] ? '1' : '0';
                
                $action_array = array();
                $post_args = array('post_type' => 'hs-action', 'numberposts' => -1);
                $hs_actions = get_posts($post_args);
                foreach ($hs_actions as $hs_action) {
                    if($instance['action_ids_' . $hs_action->ID] == '1'){
                   // if(isset($instance['action_ids_' . $hs_action->ID])){
                        array_push($action_array, $hs_action->ID);
                    }
                }
        $action_ids =  implode(",", $action_array);

                echo do_shortcode($myhubspotwp_action->hs_display_action($before_widget, $after_widget, $before_title, $after_title, $hide_title, $action_ids));
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
                global $myhubspotusage;
                $myhubspotusage->check_widget('Add/Update CTA Widget');
                
        $instance = $old_instance;
        $instance['hide_title'] = $new_instance['hide_title'] ? 1 : 0;

                $args = array('post_type' => 'hs-action', 'numberposts' => -1);
                $hs_actions = get_posts($args);
                foreach ($hs_actions as $hs_action) {
                    $instance['action_ids_' . $hs_action->ID] = $new_instance['action_ids_' . $hs_action->ID] ? 1 : 0;
                }
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
            $default_instance = array('hide_title' => '');
            $args = array('post_type' => 'hs-action', 'numberposts' => -1);
            $hs_actions = get_posts($args);
            foreach ($hs_actions as $hs_action) {
                $action_key = 'action_ids_' . $hs_action->ID;
                $default_instance[$action_key] = 0;
            }
            $instance = wp_parse_args($instance, $default_instance);
            ?>
            <p><input class="checkbox" type="checkbox" <?php checked($instance['hide_title'], '1'); ?> id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" /> <label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide Title'); ?></label></p>
            <p><strong>Select Calls to Action:</strong><br />
            <?php
            foreach ($hs_actions as $hs_action) {
                setup_postdata($hs_action); ?>
                <input class="checkbox" type="checkbox" <?php checked($instance['action_ids_' . $hs_action->ID], '1'); ?> value="<?php _e($hs_action->ID); ?>" name="<?php echo $this->get_field_name('action_ids_' . $hs_action->ID); ?>" id="<?php echo $this->get_field_id('hs-action-' . $hs_action->ID); ?>" /> <label for="<?php echo $this->get_field_id('hs-action-' . $hs_action->ID); ?>"><?php _e($hs_action->post_title); ?></label>
                <br />
                <?php
            }
        ?>
        </p>

        <?php
    }

} // class hs_Widget 

//=============================================
// Create Subscribe Widget
//=============================================
class HubSpot_EmailSubscribe_Widget extends WP_Widget {
    
    /** constructor */
    function HubSpot_EmailSubscribe_Widget() {
        $this->WP_Widget(false, $name = 'HubSpot: Subscribe Form'); 
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) { 

        extract( $args );
        $hs_settings = array();
        $hs_settings = get_option('hs_settings');
        $hide_fb_link = $instance['hide_fb_link'] ? '1' : '0';
        $title = $instance['title'];
        
        $fb_name = str_replace('http://feeds.feedburner.com/','',$hs_settings['hs_feedburner_url']);
        
        
        echo $before_widget;        
        if (trim($title) != ""){
            echo $before_title . $title . $after_title; 
        }
        echo '<form style="text-align:center;" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open(\'http://feedburner.google.com/fb/a/mailverify?uri=' . $fb_name . '\', \'popupwindow\', \'scrollbars=yes,width=550,height=520\');return true">';
        echo '<p>Enter your email address:</p>';
        echo '<p><input type="text" name="email"/></p>';
        echo '<input type="hidden" value="' . $fb_name . '" name="uri"/><input type="hidden" name="loc" value="en_US"/><input type="submit" value="Subscribe" />';
        if(!$hide_fb_link){
            echo '<p>Delivered by <a href="http://feedburner.google.com" target="_blank">FeedBurner</a></p>';
        }
        echo '</form>';
        
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
                global $myhubspotusage;
                $myhubspotusage->check_widget('Add/Update Email Subscribe Widget');
                
        $instance = $old_instance;
        $instance['hide_fb_link'] = $new_instance['hide_fb_link'] ? 1 : 0;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $instance = wp_parse_args($instance, array('hide_fb_link' => '', 'title' => ''));
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></label></p>
        <p><input class="checkbox" type="checkbox" <?php checked($instance['hide_fb_link'], '1'); ?> id="<?php echo $this->get_field_id('hide_fb_link'); ?>" name="<?php echo $this->get_field_name('hide_fb_link'); ?>" /> <label for="<?php echo $this->get_field_id('hide_fb_link'); ?>"><?php _e('Hide Feedburner Link'); ?></label></p>
        <?php 
    }

} // class hs_Widget
?>