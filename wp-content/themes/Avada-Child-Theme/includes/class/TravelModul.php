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

  public function getTermData( $id )
  {
    $q = "SELECT
      c.*
    FROM travel_term_config as c
    WHERE 1=1 and
    c.post_id = %d and
    c.ID = %d
    LIMIT 0,1
    ";

    $data = $this->db->get_results( $this->db->prepare($q, $this->postid, $id) );
    $data = $this->prepareTermConfigs( $data[0]->termgroup, $data );
    $back = $data[0];

    return $back;
  }

  public function getConfigData( $group, $id )
  {
    $back = array();

    $q = "SELECT
      c.*
    FROM travel_term_config as c
    WHERE 1=1 and
    c.termgroup = '%s' and
    c.post_id = %d and
    c.ID = %d
    LIMIT 0,1
    ";

    $data = $this->db->get_results( $this->db->prepare($q, $group, $this->postid, (int)$id) );
    $data = $this->prepareTermConfigs( $data[0]->termgroup, $data );
    $back = $data[0];

    return $back;
  }

  public function getRooms()
  {
    $back = array();
    $q = "SELECT
      r.*,
      td.travel_year,
      td.travel_month,
      td.travel_day,
      td.utazas_duration_id
    FROM travel_rooms as r
    LEFT OUTER JOIN travel_dates as td ON td.ID = r.date_id
    WHERE 1=1 and
    r.post_id = %d
    ORDER BY td.travel_year ASC, td.travel_month ASC, td.travel_day ASC, r.title ASC
    ";

    $data = $this->db->get_results( $this->db->prepare($q, $this->postid) );

    foreach ( (array)$data as $d ) {
      $duration = $this->getTermValuById('utazas_duration', (int)$d->utazas_duration_id);
      $back[$d->date_id]['date_on'] = $d->travel_year.' / '.$d->travel_month.' / '.$d->travel_day;
      $back[$d->date_id]['ID'] = (int)$d->date_id;
      $back[$d->date_id]['day'] = $duration;
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['ID'] = (int)$d->ellatas_id;
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['ellatas'] = $this->getTermValuById('utazas_ellatas', (int)$d->ellatas_id);
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['rooms'][] = $this->prepareRoomValues($d);
    }
    unset($data);

    return $back;
  }

  public function getRoomData( $id )
  {
    $back = array();
    $q = "SELECT
      r.*,
      td.travel_year,
      td.travel_month,
      td.travel_day,
      td.utazas_duration_id
    FROM travel_rooms as r
    LEFT OUTER JOIN travel_dates as td ON td.ID = r.date_id
    WHERE 1=1 and
    r.post_id = %d and r.ID = %d
    ";

    $data = $this->db->get_results( $this->db->prepare($q, $this->postid, $id) );
    $back = $this->prepareRoomValues($data[0]);
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

      if( $key == 'active') {
        $value = ($value == 1) ? true : false;
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
          $s->requireditem = ($s->requireditem == '0') ? false : true;
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

  public function saveRooms( $data = array() )
  {
    $back = array();

    // check item
    foreach ( (array)$data as $d ) {
      if ( $d['title'] == '' ) {
        throw new \Exception("Hiba: a szoba megnevezése kötelező.");
      }
      if ( $d['date_id'] == 0 ) {
        throw new \Exception("Hiba: a szobához az elérhető időpont kiválasztása kötelező.");
      }
      if ( $d['ellatas_id'] == 0 ) {
        throw new \Exception("Hiba: a szobához az elérhető ellátás kiválasztása kötelező.");
      }
    }

    foreach ( (array)$data as $d ) {
      $this->db->insert(
        'travel_rooms',
        array(
          'post_id' => $this->postid,
          'title' => $d['title'],
          'description' => ($d['description'] == '') ? NULL : $d['description'],
          'date_id' => (int)$d['date_id']['ID'],
          'ellatas_id' => (int)$d['ellatas_id']['term_id'],
          'adult_price' => (int)$d['adult_price'],
          'child_price' => (int)$d['child_price'],
          'adult_capacity' => (int)$d['adult_capacity'],
          'child_capacity' => (int)$d['child_capacity'],
          'active' => ($d['active'] == 'true') ? 1 : 0,
        ),
        array('%d', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d')
      );

      $back['inserted_id'][] = $this->db->insert_id;
    }

    return $back;
  }

  public function saveConfigData( $id, $datas )
  {
    $this->db->update(
    	'travel_term_config',
    	array(
    		'title' => $datas['title'],
    		'description' => $datas['description'],
        'price' => (float)$datas['price'],
        'price_calc_mode' => $datas['price_calc_mode'],
        'requireditem' => ($datas['requireditem'] == 'false') ? 0 : 1
    	),
    	array( 'ID' => (int)$id ),
    	array(
    		'%s',
    		'%s',
        '%d',
        '%s',
        '%d',
    	),
    	array( '%d' )
    );

    return true;
  }

  public function deleteConfigData( $id )
  {
    $this->db->delete(
      'travel_term_config',
      array( 'ID' => $id ),
      array( '%d' )
    );
  }

  public function saveConfigTerm( $group = false, $data = array() )
  {
    $back = array();

    if ( !$group ) {
      throw new \Exception("Hiba: hiányzik a csoportazonosító kulcs az elem mentéséhez.");
    }

    if ( $group == 'szobak' ) {
      return $this->saveRooms( $data );
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
    $rowdata->durration= $this->getTermValuById('utazas_duration', $rowdata->utazas_duration_id );
    $rowdata->onday= $rowdata->travel_year.' / '.$rowdata->travel_month.' / '.$rowdata->travel_day.' ('.$rowdata->durration->name.')';

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
