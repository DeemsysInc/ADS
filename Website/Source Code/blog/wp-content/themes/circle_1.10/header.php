<!DOCTYPE html>
<html <?php language_attributes(); ?>>              
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />                   
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php kopa_print_page_title(); ?></title>                
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />       
        <link rel="shortcut icon" type="image/x-icon"  href="<?php echo get_option('kopa_theme_options_favicon_url'); ?>">     
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_option('kopa_theme_options_apple_iphone_icon_url'); ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_option('kopa_theme_options_apple_ipad_icon_url'); ?>">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_option('kopa_theme_options_apple_iphone_retina_icon_url'); ?>">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_option('kopa_theme_options_apple_ipad_retina_icon_url'); ?>">      
        <?php wp_head(); ?>
    </head>    
    <body <?php body_class(); ?>>                                      
        <header id="page-header">

            <div id="header-top">
                <div class="wrapper">
                    <div class="row-fluid">
                        <div class="span12">
                            <?php
                            if (has_nav_menu('main-nav')):
                                wp_nav_menu(
                                        array(
                                            'theme_location' => 'main-nav',
                                            'container' => 'nav',
                                            'container_id' => 'main-nav',
                                            'container_class' => 'clearfix',
                                            'menu_id' => 'main-menu',
                                            'menu_class' => 'clearfix'
                                        )
                                );
                            
                            ?>
                            <nav id="mobile-menu">
                                <span><?php echo __('Menu', kopa_get_domain());?></span>
                                <?php
                                     wp_nav_menu(
                                        array(
                                            'theme_location' => 'main-nav',
                                            'container' => false,
                                            'menu_id' => 'toggle-view-menu',
                                            'menu_class' => 'clearfix',
                                            'walker'        => new kopa_mobile_menu
                                        )
                                );
                                ?>
                            </nav>
                            <?php
                            endif;
                            $kopa_theme_options_email_address = get_option('kopa_theme_options_email_address');
                            $kopa_theme_options_phone_number = get_option('kopa_theme_options_phone_number');
                            if($kopa_theme_options_email_address or $kopa_theme_options_phone_number):
                            ?>   
                            <ul class="contact-top clearfix">
                                <?php if($kopa_theme_options_phone_number):?>
                                    <li class="clearfix"><i class="icon-phone"></i><span><?php echo $kopa_theme_options_phone_number; ?></span></li>
                                <?php endif;
                                if($kopa_theme_options_email_address):
                                ?>
                                    <li class="clearfix"><i class="icon-envelope"></i><a href="mailto:<?php echo $kopa_theme_options_email_address;?>"><?php echo $kopa_theme_options_email_address;?></a></li>
                                <?php endif;?>
                            </ul><!--contact-top-->
                            <?php endif;?>
                        </div><!--span12-->
                    </div><!--row-fluid-->
                </div><!--wrapper-->
            </div><!--header-top-->

            <div id="header-bottom">
                <div class="wrapper">
                    <div class="row-fluid">
                        <div class="span12 clearfix">
                            <div id="logo-image">
                                <a href="<?php echo home_url(); ?>"><img src="<?php echo get_option('kopa_theme_options_logo_url'); ?>" alt="" /></a>
                            </div><!--logo-image-->
                            <div class="social-box clearfix">
                                <?php
                                $social_networks = array(
                                    'facebook' => array('icon-facebook', __('Facebook', kopa_get_domain())),
                                    'twitter' => array('icon-twitter', __('Twitter', kopa_get_domain())),
                                    'pinterest' => array('icon-pinterest', __('Pinterest', kopa_get_domain())),
                                    'google' => array('icon-google-plus', __('Google', kopa_get_domain())),
                                    'dribbble' => array('icon-dribbble', __('Dribbble', kopa_get_domain())),
                                    'linkedin' => array('icon-linkedin', __('Linkedin', kopa_get_domain())),
                                    'deviantart' => array('icon-deviantart', __('Deviantart', kopa_get_domain())),
                                    'wordpress' => array('icon-wordpress', __('Wordpress', kopa_get_domain())),
                                    'youtube' => array('icon-youtube', __('Youtube', kopa_get_domain())),
                                    'flickr' => array('icon-flickr', __('Flickr', kopa_get_domain())),
                                    'vimeo' => array('icon-vimeo', __('Vimeo', kopa_get_domain()))
                                );
                                ?>
                                <ul class="socials-link clearfix">
                                    <?php
                                    $rss_url = get_option("kopa_theme_options_social_links_rss_url", FALSE);

                                    if (strtolower($rss_url) != 'hide') {
                                        if ($rss_url) {
                                            ?>
                                            <li class="feed-icon"><a href="<?php echo $rss_url; ?>" target="_blank" title="<?php echo __('RSS', kopa_get_domain()); ?>"><span class="icon-feed-2" aria-hidden="true"></span></a></li>                   
                                            <?php
                                        } else {
                                            ?>
                                            <li class="feed-icon"><a href="<?php echo get_bloginfo('rss2_url'); ?>" target="_blank" title="<?php echo __('RSS', kopa_get_domain()); ?>"><span class="icon-feed-2" aria-hidden="true"></span></a></li>                    
                                            <?php
                                        }
                                    }
                                    ?>

                                    <?php
                                    foreach ($social_networks as $slug => $title):
                                        $url = get_option("kopa_theme_options_social_links_{$slug}_url", FALSE);
                                        if ($url):
                                            ?>
                                            <li class="<?php echo "{$slug}-icon"; ?>"><a href="<?php echo $url; ?>" title="<?php echo $title[1]; ?>" target="_blank"><span class="<?php echo $title[0]; ?>" aria-hidden="true"></span></a></li>
                                            <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </ul>
                                <?php get_search_form(); ?>                               
                            </div><!--social-box-->


                        </div><!--span12-->
                    </div><!--row-fluid-->
                </div><!--wrapper-->
            </div><!--header-bottom-->

        </header><!--page-header-->

        <?php kopa_breadcrumb(); ?>

        <div id="main-content">