<?php
class WordpressSlideshow{

  public $id;
  public $slideshow_name;

  protected $_slides;

  protected function get_slides(){

    if(empty($_slides)){

      $_slides = WordpressSlideshow_Slide::findBySlideshow($this);
    }

    return $_slides;
  }

  function __construct($slideshow_name,$id=null){

    $this->id = $id;
    $this->slideshow_name = $slideshow_name;
  }

  /* PERSISTANCE MANAGEMENT */

  /**
   * Finds slideshow with given id
   * @param $slideshow_id The id of the slideshow
   * @return WordpressSlideshow Found slideshow, or null if none is found
   */
  public static function find($slideshow_id){

    global $wpdb;
    
    $query = $wpdb->prepare('SELECT * FROM '.WORDPRESS_SLIDESHOW_TABLE.' WHERE ID=%s',$slideshow_id);
    $results = $wpdb->get_results($query);

    if(is_array($results)){

      $result = $results[0];
      return new WordpressSlideshow($result->slideshow_name,$result->ID);
    }
    else{

      return null;
    }
  }

  public static function findAll(){

    global $wpdb;

    $query = $wpdb->prepare('SELECT * FROM '.WORDPRESS_SLIDESHOW_TABLE);
    $results = $wpdb->get_results($query);

    $list = array();
    if($results){

      foreach($results as $result){

        $slideshow = new WordpressSlideshow($result->slideshow_name,$result->ID);
        $list[$slideshow->id] = $slideshow;
      }
    }
    return $list;
  }

  public function save(){

    if($this->id == null){

      $this->insert();
    }
    else{

      $this->update();
    }
  }

  public function delete(){

    global $wpdb;
    if(!empty($this->id)){
      $query = $wpdb->prepare('DELETE FROM '.WORDPRESS_SLIDESHOW_TABLE.' WHERE ID=%s',$this->id);
      $result = $wpdb->query($query);

      if(!$result){

        throw new Exception(__('An error occured during the slideshow deletion','wordpress-slideshow'));
      }

      $this->id = null;
    }
  }

  private function insert(){

    global $wpdb;
    $query = $wpdb->prepare('INSERT INTO '.WORDPRESS_SLIDESHOW_TABLE.' (slideshow_name) VALUES (%s);',$this->slideshow_name);
    $result = $wpdb->query($query);

    if(!$result){

      $error = $wpdb->last_error;
      if(strstr($error,'UNIQUE')){
        $message = __('The slideshow name must be unique','wordpress-slideshow');
      }
      else{

        $message = __('An error occured during the slideshow creation','wordpress-slideshow');
      }

      throw new Exception($message);
    }

    $this->id = $wpdb->insert_id;
  }

  private function update(){

    global $wpdb;

    $query = $wpdb->prepare('UPDATE '.WORDPRESS_SLIDESHOW_TABLE.' SET slideshow_name = %s WHERE ID=%s;',$this->slideshow_name,$this->id);
    $result = $wpdb->query($query);

    if(!$result){

      $error = $wpdb->last_error;
      if(strstr($error,'UNIQUE')){
        $message = __('The slideshow name must be unique','wordpress-slideshow');
      }
      else{

        $message = __('An error occured during the slideshow update','wordpress-slideshow');
      }

      throw new Exception($message);
    }
  }

  public function __get($name){

    if($name == 'slides'){

      return $this->get_slides();
    }
  }
}
?>
