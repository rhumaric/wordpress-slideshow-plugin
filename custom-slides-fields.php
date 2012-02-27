<?php
  // Default values
  $url = "http://";
  $name = "";
  $image_url = "http://";
  $text = "";

  // Value population with $slide variable
  if(!empty($slide)){

    $url = $slide->url;
    $name = $slide->name;
    $image_url = $slide->image_url;
    $text = $slide->text;
    $slide_no = $slide->no;
  }
?>
<label class="howto" for="custom-slide-url">
<span><?php _e('URL','wordpress-slideshow'); ?></span>
<input name="custom-slide-url" type="url" value="<?php echo $url; ?>">
</label>

<label class="howto" for="custom-slide-name">
<span><?php _e('Title','wordpress-slideshow'); ?></span>
<input name="custom-slide-name" type="text" value="<?php echo $name; ?>"> 
</label>

<label class="howto" for="custom-slide-image-url">
  <span> <?php _e('Image URL','wordpress-slideshow'); ?></span>
  <input name="custom-slide-image-url" type="url" value="<?php echo $image_url ?>">
</label>

<label class="howto" for="custom-slide-text">
  <span> <?php _e('Slide text','wordpress-slideshow'); ?></span>
  <textarea name="custom-slide-text" rows="4"><?php echo $text; ?></textarea>
</label>

<label class="howto" for="custom-slide-no">
  <span><?php _e('Slide N°','wordpress-slideshow'); ?></span>
  <input name="custom-slide-no" type="number" value="<?php echo $slide_no ?>">
</label>
