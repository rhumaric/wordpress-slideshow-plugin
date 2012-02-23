<?php 
$slides = $active_slideshow->slides;
?>
<ul>
  <?php foreach($slides as $slide):?>
    <li>
      <article class="slide">
        <header class="menu-item-handle">
          <h1 class="item-title"><?php echo $slide->name; ?></h1>
        </header>
        <div class="menu-item-settings">
          <?php include(WORDPRESS_SLIDESHOW_DIR.'custom-slides-fields.php'); ?>
          <form method="post" action="<?php echo $action; ?>" class="delete-action">
            <input type="hidden" name="slide" value="<?php echo $slide->id; ?>">
            <input type="submit" name="delete-slide" value="<?php _e('Remove slide','wordpress-slideshow'); ?>" class="submitdelete">
          </form>
        </div>
      </article>
    </li>
  <?php endforeach; ?>
</ul>
