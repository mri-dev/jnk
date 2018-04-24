<?php
class TravelModul
{
  private $db = null;
  private $postid = 0;
  private $termgroups = array('szolgaltatas', 'programok');
  private $calcmode = array('once', 'daily', 'once_person', 'day_person');

  public function __construct( $postid )
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->postid = $postid;

    return $this;
  }

  public function getTermsConfigs()
  {
    $back = array();

    foreach ( $this->termgroups as $tg )
    {
      $q = "SELECT
        c.*
      FROM travel_term_config as c
      WHERE 1=1 and
      c.termgroup = %s
      ORDER BY c.title ASC
      ";
      $data = $this->db->get_results( $this->db->prepare($q, $tg) );
      $data = $this->prepareTermConfigs( $tg, $data );
      $back[$tg] = $data;
    }

    return $back;
  }

  public function getRooms()
  {
    $back = array();
    $q = "SELECT
      r.*,
      td.travel_year,
      td.travel_month,
      td.travel_day
    FROM travel_rooms as r
    LEFT OUTER JOIN travel_dates as td ON td.ID = r.date_id
    WHERE 1=1 and
    r.post_id = %d
    ORDER BY td.travel_year ASC, td.travel_month ASC, td.travel_day ASC, r.title ASC
    ";

    $data = $this->db->get_results( $this->db->prepare($q, $this->postid) );

    foreach ( (array)$data as $d ) {
      $back[$d->date_id]['date_on'] = $d->travel_year.' / '.$d->travel_month.' / '.$d->travel_day;
      $back[$d->date_id]['ID'] = (int)$d->date_id;
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['ID'] = (int)$d->ellatas_id;
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['ellatas'] = $this->getTermValuById('utazas_ellatas', (int)$d->ellatas_id);
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['rooms'][] = $this->prepareRoomValues($d);
    }
    unset($data);

    return $back;
  }

  private function prepareRoomValues( $data )
  {
    $back = array();

    foreach ( $data as $key => $value ) {
      if (is_numeric($value)) {
        $value = (int)$value;
      }
      $back[$key] = $value;
    }
    unset($data);

    return $back;
  }

  private function prepareTermConfigs( $group, $set )
  {
    switch ($group) {
      case 'szolgaltatas': case 'programok':
        $reset = array();
        foreach ($set as $s) {
          $s->price_after = ' Ft'.$this->priceTypeText($s->price_calc_mode);
          $reset[] = $s;
        }
        $set = $reset;
        unset($reset);
      break;
    }
    return $set;
  }

  private function priceTypeText( $type )
  {
    switch ($type) {
      case 'daily':
        return '/nap';
      break;
      case 'once':
        return '';
      break;
      case 'once_person':
        return '/fő';
      break;
      case 'day_person':
        return '/fő/nap';
      break;
    }
  }

  public function saveConfigTerm( $group = false, $data = array() )
  {
    if ( !$group ) {
      throw new \Exception("Hiba: hiányzik a csoportazonosító kulcs az elem mentéséhez.");
    }

    // check item
    foreach ( (array)$data as $d ) {
      if ( $d['title'] == '' ) {
        throw new \Exception("Hiba: az elem(ek) megnevezése kötelező.");
      }

      if ( $d['price_calc_mode'] == '0' ) {
        throw new \Exception("Hiba: az elem(ek)nél kötelezően ki kell választani az ár jellegét.");
      }
    }

    foreach ( (array)$data as $d ) {
      $this->db->insert(
        'travel_term_config',
        array(
          'post_id' => $this->postid,
          'title' => $d['title'],
          'description' => ($d['description'] == '') ? NULL : $d['description'],
          'termgroup' => $group,
          'price' => (int)$d['price'],
          'price_calc_mode' => $d['price_calc_mode'],
          'requireditem' => ($d['requireditem'] == 'true') ? 1 : 0,
        ),
        array('%d','%s','%s','%s','%d','%s','%d')
      );

      $back['inserted_id'][] = $this->db->insert_id;
    }

    return $back;
  }

  public function saveDates( $data = array() )
  {
    $back = array();

    if (empty($data)) {
      return false;
    }

    foreach ( (array)$data as $d ) {
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
    $rowdata->onday= $rowdata->travel_year.' / '.$rowdata->travel_month.' / '.$rowdata->travel_day;
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
