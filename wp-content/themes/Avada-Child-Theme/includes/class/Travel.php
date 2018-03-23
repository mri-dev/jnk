<?php
/**
 * Travel class for Searcher items
 */
class Travel
{
  public $id = null;
  private $tpost = null;
  private $data;
  public $arg = array();

  public function __construct( \WP_Post $travel, $arg = array() )
  {
     $this->arg = array_replace( $this->arg, $arg );

     $this->id = $travel->ID;
     $this->tpost = $travel;

     return $this;
  }

  public function Title()
  {
    return $this->tpost->post_title;
  }

  public function Image()
  {
    return get_the_post_thumbnail_url( $this->tpost );
  }

  public function Url()
  {
    return get_post_permalink( $this->tpost );
  }

  public function __destruct()
  {
    $this->arg = null;
  }
}

?>
