<?php
    $dir = wp_upload_dir(); 
    $path = $dir['basedir'] . '/rt-camp_wp-slideshow/'; 
    $files = scandir($path); 

    global $wpdb;
    $table_name = $wpdb->prefix . 'rtcamp_slideshow';   
    $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY `slide_order` ASC", OBJECT );

    if($files && $results) {
?>
    <hr />
    <h2>Uploaded Images:</h2>
    <ul id="image-list" class="image-list">
<?php 
    foreach($results as $file) {
?>
    <li id="rowid_<?php echo $file->id; ?>">
        <ul class="imglist-row">
            <li class="sort-hover"><i class="fa-solid fa-grip-lines-vertical"></i></li>
            <li class="img-list-container"><img src="<?php echo $file->file_url; ?>" alt="<?php echo $file->file_name; ?>"></li>
            <li>
                <button class="img-remove" name="remove" id="img_<?php echo $file->id; ?>" data-id="<?php echo $file->id; ?>">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </li>
        </ul>
    </li>
<?php 
    }
?>
    </ul>
<?php } ?>