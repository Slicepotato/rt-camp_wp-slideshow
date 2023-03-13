<?php
    $dir = wp_upload_dir(); 
    $path = $dir['basedir'] . '/rt-camp_wp-slideshow/'; 
    $files = scandir($path); 

    global $wpdb;
    $table_name = $wpdb->prefix . 'rtcamp_slideshow';   
    $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY `slide_order` ASC", OBJECT );

    if($files && $results) {
?>
<div class="carousel-container">
    <div class="rtcamp-carousel slick-carousel">
<?php
    foreach($results as $file) {
        echo '<div id="slide_' . $file->id . '"><img src="' . $file->file_url . '" /></div>';
    }
?>
    </div>
    <ul class="slick-nav">
        <li class="prev">
            <button type="button"><i class="fa-solid fa-angle-left"></i></button>
        </li>
        <li class="next">
            <button type="button"><i class="fa-solid fa-angle-right"></i></button>
        </li>
    </ul>
</div>
<?php 
    }
?>