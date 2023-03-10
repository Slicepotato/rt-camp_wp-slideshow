<?php
/**
    * Plugin Name: rtCamp Slideshow Plugin
    * Plugin URI: https://github.com/Slicepotato/rt-camp_wp-slideshow
    * Description: Wordpress Plugin Challenge Submission
    * Version: 1
    * Author: Michael Johnson
    * Author URI: https://www.codemjohnson.com/
    **/

    /* Add Dashicons in WordPress Front-end */
    
    function load_dashicons_front_end() {
        wp_enqueue_style( 'dashicons' );
    }
    add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );

    function rtcslide_options_page_html() {
    ?>
        <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
                settings_fields( 'rtcamp_slideshow' );
                do_settings_sections( 'rtcslide' );
                submit_button( __( 'Save Settings', 'textdomain' ) );
            ?>
        </form>
        </div>
    <?php
    }
    add_action( 'admin_menu', 'rtc_slideshow_page' );
    function rtc_slideshow_page() {
        add_menu_page(
            'rtCamp Slideshow',                                 // page title
            'rtCamp Slideshow',                                 // menu title
            'manage_options',                                   // capability
            'rtcslide',                                         // menu slug
            'rtcslide_options_page_html',                       // callable
            'dashicons-slides',                                 // dashicon
            20                                                  // menu position/order
        );
    }
?>