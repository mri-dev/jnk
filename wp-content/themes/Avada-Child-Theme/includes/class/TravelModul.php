<?php
class TravelModul
{
  private $db = null;
  private $postid = 0;
  private $termgroups = array('szolgaltatas', 'programok', 'biztositas');
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
      c.post_id = %d and
      c.termgroup = %s
      ORDER BY c.title ASC
      ";
      $data = $this->db->get_results( $this->db->prepare($q, $this->postid, $tg) );
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
    $hide_older_dates = true;
    $back = array();
    $q = "SELECT
      r.*,
      td.travel_year,
      td.travel_month,
      td.travel_day,
      td.utazas_duration_id,
      TIMESTAMP(CONCAT(td.travel_year,'-',td.travel_month,'-',td.travel_day)) as ts
    FROM travel_rooms as r
    LEFT OUTER JOIN travel_dates as td ON td.ID = r.date_id
    WHERE 1=1 and
    r.post_id = %d ";

    if ($hide_older_dates) {
      $q .= " HAVING (ts >= TIMESTAMP(now()) and r.date_id != 0) or r.date_id = 0 ";
    }

    $q .= " ORDER BY td.travel_year ASC, td.travel_month ASC, td.travel_day ASC, r.title ASC";

    $data = $this->db->get_results( $this->db->prepare($q, $this->postid) );

    foreach ( (array)$data as $d )
    {
      $duration = $this->getTermValuById('utazas_duration', (int)$d->utazas_duration_id);
      $back[$d->date_id]['date_on'] = $d->travel_year.' / '.$d->travel_month.' / '.$d->travel_day;
      $back[$d->date_id]['ID'] = (int)$d->date_id;
      $back[$d->date_id]['day'] = $duration;
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['ID'] = (int)$d->ellatas_id;
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['ellatas'] = $this->getTermValuById('utazas_ellatas', (int)$d->ellatas_id);
      $room = $this->prepareRoomValues($d);
      $back[$d->date_id]['ellatas'][$d->ellatas_id]['rooms'][$room['ID']] = $room;
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

  public function getDateData( $id )
  {
    $q = "SELECT
      td.*
    FROM travel_dates as td
    WHERE 1=1 and
    td.post_id = {$this->postid} and
    td.ID = {$id}
    ";

    $data = $this->db->get_results( $q );
    $back = $this->prepareDateRow($data[0]);

    return $back;
  }

  public function createTestimonial( $content )
  {
    $insert = array();
    $insert['post_type'] = 'wpm-testimonial';
    $insert['post_title'] = $content['client_name'];
    $insert['post_content'] = $content['msg'];
    $insert['post_status'] = 'pending';
    $insert['meta_input'] = array(
      'client_name' => $content['client_name'],
      'destination' => $content['destination'],
      'travel_id' => $this->postid
    );

    $post_id = wp_insert_post($insert);

    wp_set_object_terms( $post_id, 22, 'wpm-testimonial-category' );

    return $post_id;
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

  public function sendPreOrder( $calculator )
  {
    $can_send = true;

    $spost = get_post( $this->postid, \ARRAY_A );
    $spost_url = get_permalink( $this->postid );
    $spost_sdesc = get_the_excerpt( $this->postid );
    $spost_img = get_the_post_thumbnail_url( $this->postid );
    $is_user_alert = false;
    $utazas_tipus = ($calculator['egyeni'] == 1) ? __('egyéni utazás', TD) : __('csoportos utazás', TD);

    if ( $can_send ) {
      // Admin értesítés
      $name = $calculator['order']['contact']['name'];
      $email = $calculator['order']['contact']['email'];
      $to = get_option('admin_email');
      $pa_text = $calculator['passengers']['adults'].' felnőtt'. ( ($calculator['passengers']['children']!=0)  ? ' + '.$calculator['passengers']['children'].' gyermek' : '');
      $mail_subject  = sprintf(__('Új %s ajnálatkérés: %s (%s)'), $utazas_tipus, $name, $pa_text);

      ob_start();
    	  include(locate_template('templates/mails/utazascalculator.php'));
        $message = ob_get_contents();
  		ob_end_clean();

      add_filter( 'wp_mail_from', array($this, 'getMailSender') );
      add_filter( 'wp_mail_from_name', array($this, 'getMailSenderName') );
      add_filter( 'wp_mail_content_type', array($this, 'getMailFormat') );

      $headers  = array();
      if (!empty($email)) {
        $headers[]  = 'Reply-To: '.$name.' <'.$email.'>';
      }

      $alert = wp_mail( $to, $mail_subject, $message, $headers );

      // User értesítés
      $is_user_alert = true;
      $name = $calculator['order']['contact']['name'];
      $email = $calculator['order']['contact']['email'];
      $to = $email;
      $pa_text = $calculator['passengers']['adults'].' felnőtt'. ( ($calculator['passengers']['children']!=0)  ? ' + '.$calculator['passengers']['children'].' gyermek' : '');
      $mail_subject  = sprintf(__('Visszaigazolás - Utazási ajánlatkérés (%s részére)'), $pa_text);

      ob_start();
    	  include(locate_template('templates/mails/utazascalculator.php'));
        $message = ob_get_contents();
  		ob_end_clean();

      add_filter( 'wp_mail_from', array($this, 'getMailSender') );
      add_filter( 'wp_mail_from_name', array($this, 'getMailSenderName') );
      add_filter( 'wp_mail_content_type', array($this, 'getMailFormat') );

      $headers  = array();
      if (!empty($email)) {
        $headers[]  = 'Reply-To: '.get_option('blogname', 'Wordpress').' <'.get_option('admin_email').'>';
      }

      wp_mail( $to, $mail_subject, $message, $headers );
    }

    return $alert;
  }

  public function priceCalcMe( $item, $date_data, $passengers )
  {
    switch ( $item['price_calc_mode'] )
    {
      case 'once':
        return 1;
      break;

      case 'daily':
        if ($date_data['durration']) {
          $n = ($date_data) ? (int)($date_data['durration']['nights']) : 0;
        }
        return $n+1;
      break;

      case 'day_person':
        $fo = (int)$passengers['adults'] + (int)$passengers['children'];
        if ($date_data['durration']) {
          $n = ($date_data) ? (int)$date_data['durration']['nights'] : 0;
        }
        return ($n+1) * $fo;
      break;

      case 'once_person':
        $fo = (int)$passengers['adults'] + (int)$passengers['children'];
        return $fo;
      break;
    }
  }

  public function priceCalcSum( $item, $date_data, $passengers )
  {
    $me = $this->priceCalcMe( $item, $date_data, $passengers );

    return (float)$item['price'] * $me;
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
      case 'biztositas':
        $reset = array();
        foreach ($set as $s) {
          $s->price_after = ' Ft'.$this->priceTypeText($s->price_calc_mode);
          $s->price = (float)$s->price;
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

    $egyeni_utazas = get_post_meta($this->postid, METAKEY_PREFIX . 'egyeni_utazas', true);

    // check item
    foreach ( (array)$data as $d ) {
      if ( $d['title'] == '' ) {
        throw new \Exception("Hiba: a szoba megnevezése kötelező.");
      }
      if ( $d['date_id'] == 0 && empty($egyeni_utazas) ) {
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

  public function saveDateData( $id, $datas )
  {
    $this->db->update(
    	'travel_dates',
    	array(
    		'utazas_duration_id' => (int)$datas['utazas_duration_id'],
        'travel_year' => (int)$datas['travel_year'],
        'travel_month' => (int)$datas['travel_month'],
        'travel_day' => (int)$datas['travel_day'],
        'active' => ($datas['active'] == 'true') ? 1 : 0
    	),
    	array( 'ID' => (int)$id ),
    	array(
    		'%d',
        '%d',
        '%d',
        '%d',
        '%d'
    	),
    	array( '%d' )
    );

    return $datas;
  }

  public function saveRoomData( $id, $datas )
  {
    $this->db->update(
    	'travel_rooms',
    	array(
    		'title' => $datas['title'],
    		'description' => $datas['description'],
        'date_id' => (int)$datas['date_id'],
        'ellatas_id' => (int)$datas['ellatas_id'],
        'adult_price' => (float)$datas['adult_price'],
        'child_price' => (float)$datas['child_price'],
        'adult_capacity' => (int)$datas['adult_capacity'],
        'child_capacity' => (int)$datas['child_capacity'],
        'active' => ($datas['active']) ? 1 : 0
    	),
    	array( 'ID' => (int)$id ),
    	array(
    		'%s',
        '%s',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
        '%d',
    	),
    	array( '%d' )
    );

    return true;
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

  public function deleteRoomData( $id )
  {
    $this->db->delete(
      'travel_rooms',
      array( 'ID' => $id ),
      array( '%d' )
    );
  }

  public function deleteDate( $id )
  {
    $this->db->delete(
      'travel_dates',
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

    // Szokák mentése
    if ( $group == 'szobak' ) {
      return $this->saveRooms( $data );
    }

    // check item
    foreach ( (array)$data as $d ) {
      if ( $d['title'] == '' && $group != 'biztositas' ) {
        throw new \Exception("Hiba: az elem(ek) megnevezése kötelező.");
      }


      if ( $d['price_calc_mode'] == '0' ) {
        throw new \Exception("Hiba: az elem(ek)nél kötelezően ki kell választani az ár jellegét.");
      }
    }

    foreach ( (array)$data as $d )
    {
      if ( $group == 'biztositas' && isset($data[0]['ID'])) {
        continue;
      }
      if ($group == 'biztositas') {
        $d['title'] = 'Utasbiztosítás';
      }

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

    if ( $group == 'biztositas' && isset($data[0]['ID']) ) {
      $this->db->update(
        'travel_term_config',
        array(
          'price' => (float)$data[0]['price'],
          'price_calc_mode' => $data[0]['price_calc_mode']
        ),
        array(
          'ID' => (int)$data[0]['ID']
        ),
        array(
          '%d', '%s'
        ),
        array( '%d' )
      );
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
          'active' => ($d['active'] == 'true') ? 1 : 0,
        ),
        array('%d','%d','%d','%d','%d','%d')
      );

      $back['inserted_id'][] = $this->db->insert_id;
    }

    return $back;
  }

  public function loadDates( $arg = array() )
  {
    $hide_older_dates = true;

    $q = "SELECT
      td.*,
      TIMESTAMP(CONCAT(td.travel_year,'-',td.travel_month,'-',td.travel_day)) as ts
    FROM travel_dates as td
    WHERE 1=1 and
    td.post_id = {$this->postid}";

    if (isset($arg['passengers'])) {
      $adults = $arg['passengers']['adult'];
      $child =  $arg['passengers']['child'];
      $data_arr = array();
      $pq = "SELECT date_id FROM `travel_rooms` WHERE post_id = {$this->postid} and active = 1 and adult_capacity >= {$adults} and child_capacity >= {$child} GROUP BY date_id";
      $pqry = $this->db->get_results($pq, ARRAY_N);
      if($pqry){
        foreach ($pqry as $di) {
          $data_arr[] = (int)$di[0];
        }
        $q .= " and td.ID IN (".implode(",", $data_arr).")";
      }

    }

    if ( $hide_older_dates ) {
      $q .= " HAVING ts >= TIMESTAMP(now()) ";
    }

    $q .= " ORDER BY td.travel_year ASC, td.travel_month ASC, td.travel_day ASC ";

    $data = $this->db->get_results( $q );

    $back = array();

    if ( isset($arg['structured']) && $arg['structured'] === true ) {
      if ( $data ) {
        foreach ($data as $d) {
          $row = $this->prepareDateRow($d);

          $back[$row->utazas_duration_id]['name'] = $row->durration->name;
          $back[$row->utazas_duration_id]['nights'] = $row->durration->nights;
          $back[$row->utazas_duration_id]['data'][$row->travel_year.'-'.$row->travel_month]['year'] = (int)$row->travel_year;
          $back[$row->utazas_duration_id]['data'][$row->travel_year.'-'.$row->travel_month]['month'] = (int)$row->travel_month;
          $back[$row->utazas_duration_id]['data'][$row->travel_year.'-'.$row->travel_month]['month_name'] = $this->monthName($row->travel_month);
          $back[$row->utazas_duration_id]['data'][$row->travel_year.'-'.$row->travel_month]['data'][] = $row;
        }
      }
    } else {
      if ($data) {
        foreach ($data as $d) {
          $back[] = $this->prepareDateRow($d);
        }
      }
    }

    return $back;
  }

  private function monthName( $month )
  {
    $month = (int)$month;
    $names = array('január', 'február', 'március', 'április', 'május', 'június', 'július', 'augusztus', 'szeptember', 'október', 'november', 'december');
    return $names[$month];
  }

  private function getWeekdayByNum( $num )
  {
    $weekdays = array('Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat');

    return $weekdays[$num];
  }

  private function prepareDateRow( $rowdata )
  {
    $rowdata->durration = $this->getTermValuById('utazas_duration', $rowdata->utazas_duration_id );
    $rowdata->onday= $rowdata->travel_year.' / '.$rowdata->travel_month.' / '.$rowdata->travel_day.' ('.$rowdata->durration->name.')';
    $rowdata->active = ($rowdata->active == '1') ? true : false;
    $rowdata->travel_weekday = $this->getWeekdayByNum(date('w', strtotime($rowdata->travel_year.'-'.$rowdata->travel_month.'-'.$rowdata->travel_day)));

    return $rowdata;
  }

  public function getTermValuById( $term, $id )
  {
    $data = wp_get_post_terms( $this->postid, $term );

    if($data) {
      foreach ($data as $d) {
        $meta = get_option('taxonomy_'.$d->term_id);
        if($d->term_id == (int)$id){
          if ($term == 'utazas_duration') {
            $d->nights = (int)$meta['nights'];
          }
          return $d;
        } else continue;
      }
    }
  }

  public function getMailFormat(){
      return "text/html";
  }

  public function getMailSender($default)
  {
    return get_option('admin_email');
  }

  public function getMailSenderName($default)
  {
    return get_option('blogname', 'Wordpress');
  }

  public function __destruct()
  {
    $this->db = null;
  }
}
?>
