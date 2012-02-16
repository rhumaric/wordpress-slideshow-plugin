<?php
$action = wordpress_slideshow_page_url().'&noheader=true';
if(!empty($active_slideshow)){

  $action .= '&slideshow='.$active_slideshow->id;
}
?>
  <form method="post" action="<?php echo $action ?>" class="customlinkdiv" id="customlinkdiv">

    <?php if(!empty($active_slideshow)):?>
      <input type="hidden" name="slideshow" value="<?php echo $active_slideshow->id; ?>">
    <?php endif; ?>
    <label class="howto" for="custom-slide-url">
    <span><?php _e('URL','wordpress-slideshow'); ?></span>
      <input name="custom-slide-url" type="url" value="http://">
    </label>

    <label class="howto" for="custom-slide-name">
    <span><?php _e('Title','wordpress-slideshow'); ?></span>
      <input name="custom-slide-name" type="text">
    </label>

    <label class="howto" for="custom-slide-image-url">
      <span> <?php _e('Image URL','wordpress-slideshow'); ?></span>
      <input name="custom-slide-image-url" type="url" value="http://">
    </label>

    <label class="howto" for="custom-slide-text">
      <span> <?php _e('Slide text','wordpress-slideshow'); ?></span>
      <textarea name="custom-slide-text" rows="4">
      </textarea>
    </label>

    <p class="button-controls">
			<span class="add-to-menu">
				<img class="waiting" src="http://wordpress.dev/wp-admin/images/wpspin_light.gif" alt="">
        <input type="submit" class="button-secondary" value="<?php _e('Add to slideshow'); ?>" name="add_custom_slide">
			</span>
		</p>

	</form><!-- /.customlinkdiv -->

