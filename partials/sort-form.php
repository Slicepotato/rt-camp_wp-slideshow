<?php
    $i = 0;
    global $wpdb;
    $table_name = $wpdb->prefix . 'rtcamp_slideshow'; 

    foreach ($_POST['order'] as $row => $id) {
          
            $wpdb->update( $table_name, array('id' => $row) );

        $i++;
    }   
?>