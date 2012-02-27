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

if(!defined(WORDPRESS_SLIDESHOW_OPTION)){

  define(WORDPRESS_SLIDESHOW_OPTION,'wordpress-slideshow-available');
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

    if(isset($_POST['update-slide'])){

      $slide = WordpressSlideshow_Slide::find($_POST['slide']);

      if(!empty($slide)){

        $slideshow = $slide->slideshow;
        $slide->name = $_POST['custom-slide-name'];
        $slide->url = $_POST['custom-slide-url'];
        $slide->image_url = $_POST['custom-slide-image-url'];
        $slide->text = $_POST['custom-slide-text'];
        if(isset($_POST['custom-slide-no'])){
          $slide->no = $_POST['custom-slide-no'];
        }

        try{
          $slide->save();
        }
        catch(Exception $e){

          $error = $e->getMessage();
        }

        $notice = __('Slide was successfully updated','wordpress-slideshow');
        #wp_redirect(wordpress_slideshow_page_url($slideshow->id).'&notice='.urlencode($notice));
      }
    }

    // Delete slide
    if(isset($_POST['delete-slide'])){

      $slide = WordpressSlideshow_Slide::find($_POST['slide']);

      if(!empty($slide)){

        $slideshow = $slide->slideshow;
        
        $slide->delete();
        $notice = __('Slide was successfully removed from slideshow','wordpress-slideshow');
        wp_redirect(wordpress_slideshow_page_url($_GET['slideshow']).'&notice='.urlencode($notice));
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

    // Save where slideshows should be displayed
    if(isset($_POST['save-slideshow-definitions'])){

      $definitions = get_option(WORDPRESS_SLIDESHOW_OPTION);

      if(empty($definitions)){

        $definitions = array();
        $create = true;
      }

      $registered_slideshows = get_registered_slideshows();

      foreach($registered_slideshows as $registered_slideshow){

        if(isset($_POST[$registered_slideshow['id']])){

          $definitions[$registered_slideshow['id']] = $_POST[$registered_slideshow['id']];
        }
      }

      if($create){

        add_option(WORDPRESS_SLIDESHOW_OPTION,$definitions);
      }
      else{

        update_option(WORDPRESS_SLIDESHOW_OPTION, $definitions);
      }

      $notice = __('The slideshow set up were saved successfully','wordpress-slideshow');
      wp_redirect(wordpress_slideshow_page_url($_GET['slideshow']).'&notice='.urlencode($notice));
      return;
    }
  }
}


function wordpress_slideshow_page_url($slideshow_id = null){

  $page_url = site_url().'/wp-admin/themes.php?page='.$_GET['page'];
  if($slideshow_id !=null){

    $page_url.='&slideshow='.$slideshow_id;
  }

  return $page_url;
}

/**
 * Adds necessary JS for wordpress slideshow admin page
 */
add_action('admin_init','wordpress_slideshow_admin_js');
function wordpress_slideshow_admin_js(){

  // jQuery
  wp_enqueue_script( 'jquery-ui-draggable' );
  wp_enqueue_script( 'jquery-ui-droppable' );
  wp_enqueue_script( 'jquery-ui-sortable' );

  wp_enqueue_script('nivo-slider',plugins_url('js/wordpress-slideshow-admin.js',__FILE__));
}

/**
 * Displays Wordpress Slideshow admin page
 */
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

/*
 * Registers a new slideshow (similar to registering a sidebar or a menu)
 * @param Array $args The properties of the slideshow 
 *       array(
 *        'name' => A human readable name
 *        'id' => An id made only of alphanumeric characters and hyphen
 *       )
 */
function register_slideshow($args){

  global $wordpress_slideshows;

  // Init the slideshow list if not already init
  if(empty($wordpress_slideshows)){

    $wordpress_slideshows = array();
  }

  // Store the slideshow parameters
  $wordpress_slideshows[$args['id']] = $args;
}

/**
 * @return boolean true if the theme register slideshows, false if not.
 */
function theme_registers_slideshow(){
  global $wordpress_slideshows;

  return !empty($wordpress_slideshows);
}

/**
 * @return int The number of slideshow the theme registers
 */
function count_registered_slideshows(){

  global $wordpress_slideshows;
  return count($wordpress_slideshows);
}

/**
 * @return array The list of slideshow the theme registers
 */
function get_registered_slideshows(){

  global $wordpress_slideshows;
  return $wordpress_slideshows;
}

/**
 * @return array The list of slideshow set up from the admin
 */
function get_set_up_slideshows(){

  $option = get_option(WORDPRESS_SLIDESHOW_OPTION);
  if(!$option){
    $option = array();
  }

  return $option;
}

add_action('wp_enqueue_scripts','enqueue_slideshow_scripts');
function enqueue_slideshow_scripts(){

  wp_enqueue_script('jquery');
  wp_enqueue_script('nivo-slider',plugins_url('lib/nivo-slider/jquery.nivo.slider.pack.js',__FILE__),array('jquery'),true);
  wp_enqueue_script('wordpress-slideshow',plugins_url('js/wordpress-slideshow.js',__FILE__),array('jquery','nivo-slider'),true);
}

add_action('wp_enqueue_scripts','enqueue_slideshow_styles');
function enqueue_slideshow_styles(){

  wp_enqueue_style('nivo-slider-default-theme',plugins_url('lib/nivo-slider/themes/default/default.css',__FILE__));
  wp_enqueue_style('nivo-slider',plugins_url('lib/nivo-slider/nivo-slider.css',__FILE__));
  wp_enqueue_style('nivo-slider-style',plugins_url('lib/nivo-slider/style.css',__FILE__));
}

/**
 * Displays a slideshow that has been registered in the theme using register_slideshow
 * @param string $slideshow_id The id of the registered slideshow to display
 */
function display_slideshow($slideshow_id){

  global $wordpress_slideshows;

  // Check that the slideshow is registered
  if(!empty($wordpress_slideshows) && !empty($wordpress_slideshows[$slideshow_id])){

    // Get the slideshow that is associated with this ID
    $slideshows = get_option(WORDPRESS_SLIDESHOW_OPTION);

    // Check that a slideshow is actually associated with that id
    if(!empty($slideshows) && !empty($slideshows[$slideshow_id])){

      $slideshow = WordpressSlideshow::find($slideshows[$slideshow_id]);

      if(!empty($slideshow)){

        include(WORDPRESS_SLIDESHOW_DIR.'slideshow.php');
      }
    }
  }
}
?>
