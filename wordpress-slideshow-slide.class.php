<?php
class WordpressSlideshow_Slide{

  public $id;
  public $name; /* The name of the slide */
  public $url; /* The url the slide links to */
  public $image_url; /* The image to display */
  public $text; /* The text to display */
  public $no; /* The N° of the slide */

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

    if($this->id == null){

      $this->insert();
    }
    else{

      $this->update();
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

  private function update(){

    global $wpdb;
    $query = $wpdb->prepare('UPDATE '.WORDPRESS_SLIDESHOW_SLIDE_TABLE ." SET slide_name = %s, slide_text = %s, slide_url = %s, slide_image_url = %s, slide_no = %d WHERE id=%d", 
      $this->name, $this->text,$this->url,$this->image_url,$this->no, $this->id);
    $result = $wpdb->query($query);
    
    if(!$result){

      echo $wpdb->last_error;
      throw new Exception(__('An error occured during slide update','wordpress-slideshow').$wpdb->last_error);
    }
  }

  public function delete(){

    global $wpdb;

    echo 'Deleting slide';
    if(!empty($this->id)){

      $query = $wpdb->prepare('DELETE FROM '.WORDPRESS_SLIDESHOW_SLIDE_TABLE.' WHERE id=%s',$this->id);
      $result = $wpdb->query($query);
      
      if(!$result){

        throw new Exception(__('An error occured when removing the slide','wordpress-slideshow'));
      }

      $this->id = null;
    }
  }

  public static function find($id){

    if(empty($id)){return;}

    global $wpdb;
    $query = $wpdb->prepare('SELECT * FROM '.WORDPRESS_SLIDESHOW_SLIDE_TABLE.' WHERE id=%s;',$id);
    $results = $wpdb->get_results($query);

    if(!is_array($results)){

      throw new Exception(__('An error occured looking up for the slides','wordpress-slideshow'));
    }

    if(empty($results)){

      return null;
    }

    $slide = WordpressSlideshow_Slide::fromQueryResult($results[0]);
    $slide->slideshow = WordpressSlideshow::find($slide->slideshow_id);

    return $slide;
  }

  public static function findBySlideshow($slideshow){

    if(empty($slideshow)){return;}

    global $wpdb;
    $query = $wpdb->prepare('SELECT * FROM '.WORDPRESS_SLIDESHOW_SLIDE_TABLE.' WHERE slideshow_id=%s ORDER BY slide_no ASC;',$slideshow->id);
    $results = $wpdb->get_results($query);

    if(!is_array($results)){

      throw new Exception(__('An error occured looking up for the slides','wordpress-slideshow'));
    }

    $slides = array();
    foreach($results as $result){

      $slide = WordpressSlideshow_Slide::fromQueryResult($result);
      $slide->slideshow = $slideshow;
      array_push($slides,$slide);
    }

    return $slides;
  }


  protected static function fromQueryResult($result){

      $slide = new WordpressSlideshow_Slide();
      $slide->id = $result->id;
      $slide->name = $result->slide_name;
      $slide->url = $result->slide_url;
      $slide->image_url = $result->slide_image_url;
      $slide->text = $result->slide_text;
      $slide->no = $result->slide_no;
      $slide->slideshow_id = $result->slideshow_id;

      return $slide;
  }

  public static function updateOrder($slide_positions){

    global $wpdb;
    foreach($slide_positions as $slide_position){

      var_dump($slide_position);
      $query = $wpdb->prepare('UPDATE '.WORDPRESS_SLIDESHOW_SLIDE_TABLE.' SET slide_no=%d WHERE id=%d',$slide_position['slide_no'],$slide_position['slide_id']);
      $wpdb->query($query);
    }
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
