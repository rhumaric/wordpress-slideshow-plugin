  <form method="post" action="<?php echo $action ?>" class="customlinkdiv" id="customlinkdiv">

    <?php if(!empty($active_slideshow)):?>
      <input type="hidden" name="slideshow" value="<?php echo $active_slideshow->id; ?>">
    <?php endif; ?>
    
    <?php include(WORDPRESS_SLIDESHOW_DIR.'custom-slides-fields.php'); ?>

    <p class="button-controls">
			<span class="add-to-menu">
				<img class="waiting" src="http://wordpress.dev/wp-admin/images/wpspin_light.gif" alt="">
        <input type="submit" class="button-secondary" value="<?php _e('Add to slideshow'); ?>" name="add_custom_slide">
			</span>
		</p>

	</form><!-- /.customlinkdiv -->

