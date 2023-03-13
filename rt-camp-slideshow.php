<?php
/**
    * Plugin Name: rtCamp Slideshow Plugin
    * Plugin URI: https://github.com/Slicepotato/rt-camp_wp-slideshow
    * Description: Wordpress Plugin Challenge Submission
    * Version: 1
    * Author: Michael Johnson
    * Author URI: https://www.codemjohnson.com/
    **/

    register_activation_hook ( __FILE__, 'on_activate' );

    function on_activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $create_table_query = "
                CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}rtcamp_slideshow` (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                `file_name` text NOT NULL,
                `file_url` text NOT NULL,
                `slide_order` mediumint(9) NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;
        ";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $create_table_query );
    }

    // Stylesheet inclusion
    function plugin_admin_styles() {
        wp_enqueue_style( 'font-awesome',  plugins_url( 'scss/vendor/font-awesome/fontawesome.min.css', __FILE__ ) );
        wp_enqueue_style( 'fa-solid',  plugins_url( 'scss/vendor/font-awesome/solid.min.css', __FILE__ ) );
        wp_enqueue_style( 'style',  plugins_url( 'style.css', __FILE__ ) );
    }
    add_action( 'admin_print_styles', 'plugin_admin_styles' );

    function plugin_user_styles() {
        wp_enqueue_style( 'font-awesome',  plugins_url( 'scss/vendor/font-awesome/fontawesome.min.css', __FILE__ ) );
        wp_enqueue_style( 'fa-solid',  plugins_url( 'scss/vendor/font-awesome/solid.min.css', __FILE__ ) );
        wp_enqueue_style( 'slick',  plugins_url( 'scss/vendor/slick.css', __FILE__ ) );
        wp_enqueue_style( 'style',  plugins_url( 'style.css', __FILE__ ) );
    }
    add_action( 'wp_enqueue_scripts', 'plugin_user_styles', 90 );

    // Scripts inclusion
    function plugin_admin_scripts() {
        $localize = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        );
        wp_register_script ( 'plugin-script', plugins_url( '/js/script.js', __FILE__ ), array('jquery','jquery-ui-sortable') );

        wp_enqueue_script('plugin-script');
        wp_localize_script( 'plugin-script', 'app', $localize);
    }
    add_action( 'admin_enqueue_scripts', 'plugin_admin_scripts' );

    function plugin_user_scripts() {
        wp_register_script ( 'slick-slider', plugins_url( '/js/slick.min.js', __FILE__ ), array('jquery') );
        wp_register_script ( 'front-end', plugins_url( '/js/front-desk.js', __FILE__ ), array('jquery','slick-slider') );

        wp_enqueue_script('slick-slider');
        wp_enqueue_script('front-end');
    }
    add_action( 'wp_enqueue_scripts', 'plugin_user_scripts' );

    function rtcslide_manage_page_html() {
        handle_upload();

        echo '<div class="wrap">';
        echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
        include_once( plugin_dir_path( __FILE__ ) . '/partials/upload-form.php' );
        echo '</div>';
    }

    function handle_upload(){
        if(isset($_POST['submit'])){
            $upload_dir = wp_upload_dir();
            $target_dir = $upload_dir['basedir'] . '/rt-camp_wp-slideshow/';

            if(!file_exists($target_dir)) {
                wp_mkdir_p($target_dir);
            }

            $file_name = basename($_FILES['imgslide']['name']);
            $target_file = $target_dir . $file_name;

            if(move_uploaded_file($_FILES['imgslide']['tmp_name'], $target_file)) {
                echo '<div class="notice notice-success is-dismissible"><p>Image uploaded successfully.</p></div>';

                global $wpdb;
                $table_name = $wpdb->prefix . 'rtcamp_slideshow';
                $wpdb->insert(
                    $table_name,
                    array(
                        'file_name' => $file_name,
                        'file_url' => $upload_dir['baseurl'] . '/rt-camp_wp-slideshow/' . $file_name,
                    )
                );
                $wpdb->update($table_name, array( 'slide_order' => $wpdb->insert_id ), array( 'id' => $wpdb->insert_id ) );
            } else {
                echo '<div class="notice notice-error is-dismissible"><p>Failed to upload image.</p></div>';
            }
        }    
    }
    
    function rtc_slideshow_page() {
        add_menu_page(
            'rtCamp Slideshow',                                 // page title
            'rtCamp Slideshow',                                 // menu title
            'manage_options',                                   // capability
            'rtcslide',                                         // menu slug
            'rtcslide_manage_page_html',                        // callable
            'dashicons-slides',                                 // dashicon
            20                                                  // menu position/order
        );
    }
    add_action( 'admin_menu', 'rtc_slideshow_page' );

    function ajax_sort_list() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rtcamp_slideshow';

        $counter = 0;
        foreach ($_POST['rowid'] as $item_id) {
            $wpdb->update($table_name, array( 'slide_order' => $counter ), array( 'id' => $item_id) );
            $counter++;
        }
        wp_die();
    }
    add_action("wp_ajax_nopriv_sort_list", "ajax_sort_list");
    add_action("wp_ajax_sort_list", "ajax_sort_list");

    function ajax_remove_slide() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rtcamp_slideshow';

        $wpdb->delete($table_name, array( 'id' => $_POST['slide_id']) );

        wp_die();
    }
    add_action("wp_ajax_nopriv_remove_slide", "ajax_remove_slide");
    add_action("wp_ajax_remove_slide", "ajax_remove_slide");

    function rtcamp_slideshow($atts){
        ob_start();
        include_once(  plugin_dir_path( __FILE__ ) . '/partials/carousel.php' );
        return ob_get_clean();  
    }
    add_shortcode('rtcampslideshow', 'rtcamp_slideshow');

    function delete_plugin_database_table(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'rtcamp_slideshow';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }
    
    register_uninstall_hook(__FILE__, 'delete_plugin_database_table');
?>