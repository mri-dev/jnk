<?php
class TravelModul
{
  private $db = null;
  private $postid = 0;
  private $termgroups = array('szolgaltatasok', 'programok', 'szobak');
  private $calcmode = array('once_person', 'day_person');

  public function __construct( $postid )
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->postid = $postid;

    return $this;
  }

  public function saveDates( $data = array() )
  {
    $back = array();

    if (empty($data)) {
      return false;
    }

    foreach ( $data as $d ) {
      $this->db->insert(
        'travel_dates',
        array(
          'utazas_duration_id' => (int)$d['utazas_duration_id']['term_id'],
          'post_id' => $this->postid,
          'travel_year' => (int)$d['travel_year'],
          'travel_month' => (int)$d['travel_month'],
          'travel_day' => (int)$d['travel_day'],
          'price_from' => (int)$d['price_from']
        ),
        array('%d','%d','%d','%d','%d','%d')
      );

      $back['inserted_id'][] = $this->db->insert_id;
    }

    return $back;
  }

  public function loadDates( )
  {
    $q = "SELECT
      td.*
    FROM travel_dates as td
    WHERE 1=1 and
    td.post_id = {$this->postid}
    ORDER BY td.travel_year ASC, td.travel_month ASC, td.travel_day ASC
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
