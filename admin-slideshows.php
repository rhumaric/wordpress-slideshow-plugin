<?php

  require_once(ABSPATH .'wp-admin/admin.php');
  require_once(ABSPATH .'wp-admin/admin-header.php');

  wp_enqueue_style('wordpress-slideshow-css', plugins_url('css/wordpress-slideshow.css',__FILE__));

  $selected_slideshow_id = isset($_GET['slideshow'])?$_GET['slideshow']:0;

// jQuery
wp_enqueue_script( 'jquery-ui-draggable' );
wp_enqueue_script( 'jquery-ui-droppable' );
wp_enqueue_script( 'jquery-ui-sortable' );

// Metaboxes
wp_enqueue_script( 'common' );
wp_enqueue_script( 'wp-lists' );
wp_enqueue_script( 'postbox' );

// Define action to post to, depending on the active slideshow id
$action = wordpress_slideshow_page_url().'&noheader=true';
if(!empty($active_slideshow)){

  $action .= '&slideshow='.$active_slideshow->id;
}

?>
<div class="wrap nav-menus-php" >
  <div id="icon-themes" class="icon32"><br></div>
  <h2><?php _e('Slideshows','wordpress-slideshow'); ?></h2>

        <?php if(!empty($notice)):?>
          <div class="updated"><?php echo $notice; ?></div>
        <?php endif; ?>
        <?php if(!empty($error)):?>
          <div class="error"><p><?php echo $error; ?></p></div>
        <?php endif; ?>
  <div id="nav-menus-frame">
    <div id="menu-settings-column" class="metabox-holder <?php if(empty($active_slideshow)){ echo 'metabox-holder-disabled'; }?>">
    <div id="side-sortables" class="metabox-sortables ui-sortables">
      <div class="postbox ">
        <div class="handlediv" title="<?php _e('Click to toggle');?>">
          <br>
       </div>
       <h3 class="hndle"><span><?php _e('Custom slide','wordpress-slideshow'); ?></span></h3>
       <div class="inside">
       <?php include (WORDPRESS_SLIDESHOW_DIR.'form-custom-slide.php'); ?>
	</div>
</div>
    
    </div>
  </div>
  <div id="menu-management-liquid">
  <div id="menu-management">
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
      <header id="nav-menu-header" class="major-publishing-action">
        <?php 
          $slideshow_name = !empty($active_slideshow)?$active_slideshow->slideshow_name:(!empty($_POST['slideshow_name'])?$_POST['slideshow_name']:'');
?>
      <form method="post" action="<?php echo wordpress_slideshow_page_url(); ?>&noheader=true" >
        <div class="major-publishing-actions">
        <label for="slideshow-name" class="howto open-label">
          <span><?php _e('Slideshow name','wordpress-slideshow'); ?>:</span>
          <input type="text" name="slideshow_name" class="regular-text menu-item-textbox" placeholder="<?php _e('Enter the name of the slideshow','wordpress-slideshow'); ?>" value="<?php echo $slideshow_name; ?>" required maxlength="45">
        </label>
        <br class="clear">
        <div class="publishing-action">
            <?php $save_label = empty($active_slideshow)?'Create slideshow':'Save slideshow'; ?>
            <input type="submit" name="save_slideshow" class="button-primary" value="<?php _e($save_label,'wordpress-slideshow'); ?>">
        </div>
        <?php if(!empty($active_slideshow)):?>
          <div class="delete-action">
            <input type="hidden" name="slideshow" value="<?php echo $active_slideshow->id;?>">
            <input type="submit" name="delete_slideshow" class="submitdelete" value="<?php _e('Delete slideshow','wordpress-slideshow'); ?>">
          </div>
        <?php endif; ?>
        </div><!-- /.major-publishing-actions -->
      </form>
      </header>
        <div id="post-body">
          <div id="post-body-content">
            <?php if(empty($active_slideshow)):?>
            <div class="post-body-plain">
              <?php printf(__('To create a new slideshow, give it a name in the above form and click "%s"','wordpress-slideshow'),__('Create slideshow','wordpress-slideshow')); ?>
            </div>
            <?php else: ?>
              <?php include(WORDPRESS_SLIDESHOW_DIR.'list-slides.php'); ?>
            <?php endif;?>
          </div>
        </div>
  </section><!-- /.slideshow-edit -->
  </div><!-- /#menu-management-liquid -->
  </div><!-- /#menu-management -->
  </div><!-- /#nav-menu-frame -->
</div><!-- /.wrap -->
