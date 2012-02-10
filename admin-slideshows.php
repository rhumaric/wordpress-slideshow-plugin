<?php

  require_once(ABSPATH .'wp-admin/admin.php');
  require_once(ABSPATH .'wp-admin/admin-header.php');

  $selected_slideshow_id = isset($_GET['slideshow'])?$_GET['slideshow']:0;
?>
<div class="wrap" >
  <div id="icon-themes" class="icon32"><br></div>
  <h2><?php _e('Slideshows','wordpress-slideshow'); ?></h2>

  <div id="menu-management">
        <?php if(!empty($notice)):?>
          <div class="updated"><?php echo $notice; ?></div>
        <?php endif; ?>
        <?php if(!empty($error)):?>
          <div class="error"><p><?php echo $error; ?></p></div>
        <?php endif; ?>
  <div class="nav-tabs-wrapper">
    <div class="nav-tabs">
       <?php  foreach($slideshows as $slideshow):?>
          <?php $innerHTML = $slideshow->slideshow_name; ?>
          <?php if($selected_slideshow_id == $slideshow->id):?>
            <span class="nav-tab nav-tab-active">
              <?php echo $innerHTML; ?>
            </span>
          <?php else: ?>
          <?php 
            $href = wordpress_slideshow_page_url($slideshow->id);
          ?>
          <a href="<?php echo $href;?>" class="nav-tab">
            <?php echo $innerHTML; ?>
          </a>
          <?php endif;?>
       <?php endforeach; ?>
          <?php 
            $innerHTML = sprintf( '<abbr title="%s">+</abbr>', esc_html__( 'Add slideshow', 'wordpress-slideshow' )); 
          ?>
       <?php if ( 0 == $selected_slideshow_id ) : ?>
          <span class="nav-tab menu-add-new nav-tab-active">
					  <?php echo $innerHTML;  ?>
				  </span>
       <?php else : ?>
          <?php
            $href = wordpress_slideshow_page_url();
          ?>
          <a class="nav-tab menu-add-new" href="<?php echo $href; ?>">
					<?php echo $innerHTML; ?>
				</a><?php endif; ?>

    </div>
  </div>
  <section class="menu-edit">
  <form method="post" action"<?php echo wordpress_slideshow_page_url(); ?>&noheader=true" >
      <header id="nav-menu-header" class="major-publishing-action">
        <?php 
          $slideshow_name = !empty($active_slideshow)?$active_slideshow->slideshow_name:(!empty($_POST['slideshow_name'])?$_POST['slideshow_name']:'');
        ?>
        <label for="slideshow-name" class="howto open-label">
          <span><?php _e('Slideshow name','wordpress-slideshow'); ?></span>
          <input type="text" name="slideshow_name" class="regular-text menu-item-textbox" placeholder="<?php _e('Enter the name of the slideshow','wordpress-slideshow'); ?>" value="<?php echo $slideshow_name; ?>" required maxlength="45">
        </label>
        <div class="publishing-action submitbox">
            <input type="submit" name="save_slideshow" class="button-primary" value="<?php _e('Create slideshow','wordpress-slideshow'); ?>">
          <?php if(!empty($active_slideshow)):?>
            <input type="hidden" name="slideshow" value="<?php echo $active_slideshow->id;?>">
            <input type="submit" name="delete_slideshow" class="submitdelete" value="<?php _e('Delete slideshow','wordpress-slideshow'); ?>">
          <?php endif; ?>
        </div>
      </header>
    </form>
  </section><!-- /.slideshow-edit -->
  </div><!-- /#menu-management -->
</div><!-- /.wrap -->
<?php


  require_once(ABSPATH .'wp-admin/admin-footer.php');
?>
