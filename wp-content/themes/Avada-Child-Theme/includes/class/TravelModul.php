<?php
class TravelModul
{
  private $db = null;
  private $postid = 0;
  public function __construct( $postid )
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->postid = $postid;

    return $this;
  }

  public function loadDates( )
  {
    $q = "SELECT
      td.*
    FROM travel_dates as td
    WHERE 1=1 and
    td.post_id = {$this->postid}
    ";

    $data = $this->db->get_results( $q );

    $back = array();

    if ($data) {
      foreach ($data as $d) {
        $back[] = $this->prepareDateRow($d);
      }
    }

    return $back;
  }

  private function prepareDateRow( $rowdata )
  {
    $rowdata->durration= $this->getTermValuById('utazas_duration', $rowdata->utazas_duration_id );
    return $rowdata;
  }

  public function getTermValuById( $term, $id )
  {
    $data = wp_get_post_terms( $this->postid, $term );

    if($data) {
      foreach ($data as $d) {
        if($d->term_id == (int)$id){
          return $d;
        } else continue;
      }
    }
  }

  public function __destruct()
  {
    $this->db = null;
  }
}
?>
