<?php $plugin_dir = WP_PLUGIN_DIR . '/rt-camp_wp-slideshow/'  ?>

<form id="upload_form" enctype="multipart/form-data" method="post">
    <p><input name="imgslide" id="upload_img" type="file" accept="image/*" /></p>
    <ul id="imgPreviewContainer" class="img-preview">
        <li><img id="imgPreview" width="300" /></li>
        <li><button id="clear_form" name="clear" type="reset">Clear</button></li>
    </ul>
    <hr />
<?php submit_button(('Upload Image') ); ?>
</form>

<?php include_once( $plugin_dir . 'partials/img-list.php' ); ?>