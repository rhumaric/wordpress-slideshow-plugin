<?php if(theme_registers_slideshow()):?>
<p><?php printf(__('Your theme uses %s slideshow(s), choose which slideshow to display below','wordpress-slideshow'), count_registered_slideshows()); ?></p>
<form method="post" action="<?php echo $action; ?>" class="slideshow-definitions">
<?php 

    $registered_slideshows = get_registered_slideshows();
    $set_up_slideshows = get_set_up_slideshows();

    foreach ($registered_slideshows as $registered_slideshow):?>
    <?php 
      
      if(isset($set_up_slideshows[$registered_slideshow['id']])){

        $set_up_slideshow = $set_up_slideshows[$registered_slideshow['id']];
      }
      else{

        $set_up_slideshow = null;
      }
    ?>
  <label class="howto" for="<?php echo $registered_slideshow['id']; ?>">
    <span><?php echo $registered_slideshow['name']; ?></span>
    <select name="<?php echo $registered_slideshow['id']; ?>">
    <option>&nbsp;</option>
    <?php foreach($slideshows as $slideshow): ?>
    <?php $selected = $slideshow->id == $set_up_slideshow ?>
    <option value="<?php echo $slideshow->id; ?>" <?php if($selected){echo "selected";} ?>>
      <?php echo $slideshow->slideshow_name; ?>
    </option>
    <?php endforeach; ?>
  </select>
  </label>
  <p class="button-controls">
  <input type="submit" name="save-slideshow-definitions" class="button-primary" value="<?php _e('Save','wordpress-slideshow'); ?>">
  </p>
</form>
<?php endforeach; ?>
<?php else: ?>
<p class="howto"><?php _e("The theme you're using does not seem to define any slideshow.",'wordpress-slideshow'); ?></p>
<?php endif; ?>
