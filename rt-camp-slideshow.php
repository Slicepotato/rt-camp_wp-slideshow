<?php
/**
    * Plugin Name: rtCamp Slideshow Plugin
    * Plugin URI: https://github.com/Slicepotato/rt-camp_wp-slideshow
    * Description: Wordpress Plugin Challenge Submission
    * Version: 1
    * Author: Michael Johnson
    * Author URI: https://www.codemjohnson.com/
    **/

    // Stylesheet inclusion
    function utm_user_scripts() {
        $plugin_url = plugin_dir_url( __FILE__ );

       wp_enqueue_style( 'style',  $plugin_url . "/style.css");
    }
    add_action( 'admin_print_styles', 'utm_user_scripts' );

    function rtcslide_manage_page_html() { ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form id="upload_form" action=<?php plugin_dir_path( __FILE__ ) . "upload.php" ?> enctype="multipart/form-data" method="post" target="messages">
            <p><input name="imgslide" id="upload_img" type="file" accept="image/*" onchange="loadFile(event)" /></p>
            <ul id="imgPreviewContainer" class="img-preview">
                <li><img id="imgPreview" width="200" /></li>
                <li><button name="clear" type="reset" onclick="resetForm(event)">Clear</button></li>
            </ul>
            <hr />
            <?php
                settings_fields( 'rtcamp_slideshow' );
                do_settings_sections( 'rtcslide' );
                submit_button( __( 'Save Settings', 'textdomain' ) );
            ?>
            </form>
        </div>
        <script>
            var loadFile = function(event) {
	            var image = document.getElementById('imgPreview');
                var imgContainer = document.getElementById('imgPreviewContainer');
                imgContainer.classList.add("show");
	            image.src = URL.createObjectURL(event.target.files[0]);
            };

            var resetForm = function(event) {
                var image = document.getElementById('imgPreview');
                var imgContainer = document.getElementById('imgPreviewContainer');
                image.src = '';
                imgContainer.classList.remove("show");
            }
        </script>
<?php
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

    /* Add Dashicons in WordPress Front-end */  
    function load_dashicons_front_end() {
        wp_enqueue_style( 'dashicons' );
    }
    add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
?>