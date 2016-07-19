<?php
class WPHubspotContact {

    function WPHubspotContact() {
        if (is_admin ()) {
            //
        } else {
            // Contact Page Hooks
            add_action('wp_print_styles', array(&$this, 'hs_contact_style'));
            add_shortcode('hs_contact', array(&$this, 'hs_create_contact_shortcode'));
        }
    }

    //=============================================
    // Add stylesheet to header
    //=============================================
    function hs_contact_style() {
        wp_enqueue_style('hubspot', HUBSPOT_URL . 'css/hubspot.css');
    }


    //=============================================
    // Add shortcode
    //=============================================
    function hs_create_contact_shortcode($atts) {
        extract(shortcode_atts(array(
                    'display' => 'both',
                    'name' => null,
                    'address' => null,
                    'citystate' => null,
                    'phone' => null
                        ), $atts));
        $hs_content = $this->hs_get_contact_info($display, $name, $address, $citystate, $phone);
        
        // Check for nested shortcodes
        $hs_content = do_shortcode($hs_content);
                
        return $hs_content;
    }

    //=============================================
    // Display contact info
    //=============================================
    function hs_get_contact_info($display, $name, $address, $citystate, $phone) {
        $content = "";
        if ($display == 'address' || $display == 'both') {
            if ($name != "") {
                $content .= $name;
            }
            if ($address != "") {
                if ($content != "") {
                    $content .= "<br />";
                };
                $content .= $address;
            }
            if ($citystate != "") {
                if ($content != "") {
                    $content .= "<br />";
                };
                $content .= $citystate;
            }
            if ($phone != "") {
                if ($content != "") {
                    $content .= "<br />";
                };
                $content .= $phone;
            }
        }

        if ($content != ""){
            $content = "<p>".$content."</p>";
        }

    
        return $content;
    }

}
?>