<?php
class WordpressSlideshow_Slide{

  public $id;
  public $name; /* The name of the slide */
  public $url; /* The url the slide links to */
  public $image_url; /* The image to display */
  public $text; /* The text to display */

  public $slideshow_id;
  protected $_slideshow;

  protected function get_slideshow(){

    return $_slideshow;
  }

  protected function set_slideshow($value){

    $this->_slideshow = $value;
    if(empty($value)){

      $this->slideshow_id = null;
    }
    else{

      $this->slideshow_id = $this->_slideshow->id;
    }
  }

  public function save(){

    if($id == null){

      $this->insert();
    }
  }

  private function insert(){

    global $wpdb;
    $query = $wpdb->prepare('INSERT INTO '.WORDPRESS_SLIDESHOW_SLIDE_TABLE. '(slide_name, slide_url, slide_image_url, slide_text, slideshow_id) VALUES(%s,%s,%s,%s,%s);',
      $this->name, $this->url, $this->image_url, $this->text, $this->slideshow_id);
    $result = $wpdb->query($query);

    if(!$result){

      throw new Exception(__('An error occured during slide creation','wordpress-slideshow'));
    }

    $this->id = $wpdb->insert_id;
  }

  public static function findBySlideshow($slideshow){

    if(empty($slideshow)){return;}

    global $wpdb;
    $query = $wpdb->prepare('SELECT * FROM '.WORDPRESS_SLIDESHOW_SLIDE_TABLE.' WHERE slideshow_id=%s;',$slideshow->id);
    $results = $wpdb->get_results($query);

    if(!$results){

      throw new Exception(__('An error occured looking up for the slides','wordpress-slideshow'));
    }

    $slides = array();
    foreach($results as $result){

      $slide = new WordpressSlideshow_Slide();
      $slide->id = $result->id;
      $slide->name = $result->slide_name;
      $slide->url = $result->slide_url;
      $slide->image_url = $result->slide_image_url;
      $slide->text = $result->slide_text;
      $slide->slideshow = $slideshow;

      array_push($slides,$slide);
    }

    return $slides;
  }

  public function __get($name){

    if($name == 'slideshow'){

      return $this->get_slideshow();
    }
  }

  public function __set($name, $value){

    if($name == 'slideshow'){

      $this->set_slideshow($value);
    }
  }
}
?>
