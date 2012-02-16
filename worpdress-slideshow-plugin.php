<?php
/*
 Plugin Name: Wordpress Slideshow plugin
 Plugin URI: https://github.com/rhumaric/wordpress-slideshow-plugin
 Version: 0.1
 Description: Plugin to display and manage slideshows
 Author: Romaric Pascal
 Author URI: http://rhumaric.com/
 */

if(!defined(WORDPRESS_SLIDESHOW_DIR)){
  define(WORDPRESS_SLIDESHOW_DIR,dirname(__FILE__).'/');
}

require_once(WORDPRESS_SLIDESHOW_DIR.'wordpress-slideshow.class.php');
require_once(WORDPRESS_SLIDESHOW_DIR.'wordpress-slideshow-slide.class.php');

global $wpdb;
if(!defined(WORDPRESS_SLIDESHOW_TABLE)){
  define(WORDPRESS_SLIDESHOW_TABLE,$wpdb->prefix.'slideshows');
}

if(!defined(WORDPRESS_SLIDESHOW_SLIDE_TABLE)){

  define(WORDPRESS_SLIDESHOW_SLIDE_TABLE,$wpdb->prefix.'slideshow_slides');
}


/* Add a new admin menu under the "Appearance" category */
add_action('admin_menu','wordpress_slideshow_menu');
function wordpress_slideshow_menu(){

  add_theme_page( 
     __('Slideshows','wordpress-slideshow'),
     __('Slideshows','wordpress-slideshow'),
     'manage_options',
     'wordpress-slideshow',
     'wordpress_slideshow_page'
  );
}

/* Handles post requests before headers are sent */
add_action('admin_init','wordpress_slideshow_handle_post');
function wordpress_slideshow_handle_post(){

  global $error;

  // Check that we are on the slideshow admin page
  if(isset($_GET['page']) && $_GET['page'] == 'wordpress-slideshow'){

    // Saves new slideshow
    if(isset($_POST['save_slideshow'])){


      $update = isset($_POST['slideshow']);
      if($update){

        $slideshow = WordpressSlideshow::find($_POST['slideshow']);
        $slideshow->slideshow_name = $_POST['slideshow_name'];
      } else {

        $slideshow = new WordpressSlideshow($_POST['slideshow_name']);
      }

      try{
        $slideshow->save();

        if($update){
          
          $message =  __('The slideshow <strong>%s</strong> was successfully updated','wordpress-slideshow');
        }
        else{

          $message = __('Your new slideshow <strong>%s</strong> was successfully created','wordpress-slideshow');
        }

        $notice = sprintf($message,$slideshow->slideshow_name);
        wp_redirect(wordpress_slideshow_page_url($slideshow->id).'&notice='.urlencode($notice));
        return;
      }
      catch(Exception $e){

        $error = $e->getMessage();
      }
    }

    // Add custom slide to slideshow
    if(isset($_POST['add_custom_slide'])){

      echo 'Creating custom slide';

      $slideshow = WordpressSlideshow::find($_POST['slideshow']);

      var_dump($_POST['slideshow']);
      var_dump($slideshow);
      if(!empty($slideshow)){

        $slide = new WordpressSlideshow_Slide();
        $slide->name = $_POST['custom-slide-name'];
        $slide->url = $_POST['custom-slide-url'];
        $slide->image_url = $_POST['custom-slide-image-url'];
        $slide->text = $_POST['custom-slide-text'];
        $slide->slideshow = $slideshow;

        try{
          $slide->save();
          $notice = __('The new slide was successfully created','wordpress-slideshow');
          wp_redirect(wordpress_slideshow_page_url($slideshow->id).'&notice='.urlencode($notice));
          return;
        }
        catch(Exception $e){

          $error = $e->getMessage();
        }
      }

      return;
    }

    // Delete new slideshow
    if(isset($_POST['delete_slideshow'])){

      $slideshow = WordpressSlideshow::find($_POST['slideshow']);
      if(!empty($slideshow)){

        $notice = sprintf(__('The slideshow <strong>%s</strong> was successfully deleted','wordpress-slideshow'),$slideshow->slideshow_name);
        $slideshow->delete();
        wp_redirect(wordpress_slideshow_page_url().'&notice='.urlencode($notice));
        return;
      }
    } 
  }
}

function wordpress_slideshow_page_url($slideshow_id = null){

  $page_url = site_url().$_SERVER['SCRIPT_NAME'].'?page='.$_GET['page'];
  if($slideshow_id !=null){

    $page_url.='&slideshow='.$slideshow_id;
  }

  return $page_url;
}

function wordpress_slideshow_page(){

  global $error;

  /* Find all slideshows, they will be used for display anyway */
  $slideshows = WordpressSlideshow::findAll();  
  $active_slideshow = $slideshows[$_GET['slideshow']];

  if(isset($_GET['notice'])){

    $notice = $_GET['notice'];
  }

  include(WORDPRESS_SLIDESHOW_DIR.'admin-slideshows.php');
}
?>
