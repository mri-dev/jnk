<?php

class AjaxRequests
{
  public function __construct()
  {
    return $this;
  }

  public function city_autocomplete()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'AutocompleteCity'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'AutocompleteCity'));
  }

  public function contact_form()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'ContactFormRequest'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'ContactFormRequest'));
  }

  public function travel_api()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'TravelAPIRequest'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'TravelAPIRequest'));
  }

  public function getterms()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'GetTermsRequest'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'GetTermsRequest'));
  }

  public function traveler()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'TravelerRequest'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'TravelerRequest'));
  }

  public function TravelerRequest()
  {
    extract($_POST);
    $return = array(
      'error' => 0,
      'msg' => '',
      'data' => array(),
      'params' => $_POST
    );

    $travels = new TravelModul((int)$_POST['postid']);

    switch ($mode)
    {
      // Ajánlatkérés elküldése
      case 'sendPreOrder':
        try {
          $re = $travels->sendPreOrder( $calculator );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Időpontok mentése
      case 'saveDates':
        try {
          $re = $travels->saveDates( $data );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      case 'getDateData':
        try {
          $re = $travels->getDateData( $id );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Dátum adatok mentése
      case 'saveDateData':
        try {
          $re = $travels->saveDateData( $id, $datas );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Dátum törlése
      case 'deleteDate':
        try {
          $re = $travels->deleteDate( $id );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // CSoport szologáltatások mentése
      case 'saveConfigTerm':
        try {
          $re = $travels->saveConfigTerm( $group, $data );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Az egyes csoportok konfigurációi
      case 'getConfigTerms':
        try {
          $re = $travels->getTermsConfigs();
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Term konfig adat mentése
      case 'saveConfigData':
        try {
          $re = $travels->saveConfigData( $id, $datas);
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Term konfig adat törlése
      case 'deleteConfigData':
        try {
          $re = $travels->deleteConfigData( $id );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Szobák betöltése
      case 'getRooms':
        try {
          $re = $travels->getRooms();
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Konfig csoportok adatai
      case 'getConfigData':
        try {
          $re = $travels->getConfigData($group, $id);
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Szoba adatok
      case 'getRoomData':
        try {
          $re = $travels->getRoomData($id);
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Szöba adatok mentése
      case 'saveRoomData':
        try {
          $re = $travels->saveRoomData( $id, $datas);
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;

      // Szoba konfig törlése
      case 'deleteRoomData':
        try {
          $re = $travels->deleteRoomData( $id );
          $return['data'] = $re;
        } catch (\Exception $e) {
          $return['error'] = 1;
          $return['msg'] = $e->getMessage();
        }
      break;


    }

    echo json_encode($return);
    die();
  }

  public function GetTermsRequest()
  {
    extract($_POST);
    $return = array(
      'error' => 0,
      'msg' => '',
      'data' => array(),
      'params' => $_POST
    );

    foreach ((array)$terms as $term) {
      $data = wp_get_post_terms($postid, $term);

      $td = array();
      foreach ((array)$data as $termkey => $termdata) {
        $termdata->metas = get_option("taxonomy_".$termdata->term_id);
        if ($term == 'utazas_duration') {
          $termdata->nights = (int)$termdata->metas['nights'];
        }
        $td[$termkey] = $termdata;
      }
      unset($data);

      $return['data'][$term] = $td;
    }

    echo json_encode($return);
    die();
  }

  public function TravelAPIRequest()
  {
    extract($_POST);
    $return = array(
      'error' => 0,
      'msg' => '',
      'data' => array(),
      'params' => $_POST
    );

    $arg = array();

    $travels = new TravelModul((int)$_POST['postid']);

    if (isset($_POST['passengers'])) {
      $arg['passengers'] = array(
        'adult' => (int)$_POST['passengers']['adults'],
        'child' => (int)$_POST['passengers']['children']
      );
      $arg['structured'] = true;
    }

    $dates = $travels->loadDates( $arg );
    $return['data'] = $dates;

    echo json_encode($return);
    die();
  }

  public function ContactFormRequest()
  {
    extract($_POST);
    $return = array(
      'error' => 0,
      'msg'   => '',
      'missing_elements' => [],
      'error_elements' => [],
      'missing' => 0,
      'passed_params' => false
    );

    $err_elements_text = '';

    $return['passed_params'] = $_POST;
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $email = $_POST['email'];
    $uzenet = $_POST['uzenet'];
    $contacttype = $_POST['formtype'];

    switch ($contacttype)
    {
      case 'kapcsolat':
        $contact_type = 'kapcsolat üzenet';
      break;
    }

    if(empty($name)) $return['missing_elements'][] = 'name';
    if(empty($email)) $return['missing_elements'][] = 'email';
    if(empty($subject)) $return['missing_elements'][] = 'subject';
    if(empty($uzenet)) $return['missing_elements'][] = 'uzenet';

    if(!empty($return['missing_elements'])) {
      $return['error']  = 1;
      $return['msg']    =  __('Kérjük, hogy töltse ki az összes mezőt az üzenet küldéséhez.',  'Avada');
      $return['missing']= count($return['missing_elements']);
      $this->returnJSON($return);
    }

    if(!empty($return['error_elements'])) {
      $return['error']  = 1;
      $return['msg']    =  __('A következő mezők hibásan vannak kitöltve',  'Avada').":\n". $err_elements_text;
      $return['missing']= count($return['missing_elements']);
      $this->returnJSON($return);
    }

    // captcha
    $captcha_code = $_POST['g-recaptcha-response'];
    $recapdata = array(
        'secret' => CAPTCHA_SECRET_KEY,
        'response' => $captcha_code
    );
    $return['recaptcha']['secret'] = CAPTCHA_SECRET_KEY;
    $return['recaptcha']['response'] = $captcha_code;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($recapdata));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $recap_result = json_decode(curl_exec($ch), true);
    curl_close($ch);
    $return['recaptcha']['result'] = $recap_result;

    if(isset($recap_result['success']) && $recap_result['success'] === false) {
      $return['error']  = 1;
      $return['msg']    =  __('Kérjük, hogy azonosítsa magát. Ha Ön nem spam robot, jelölje be a fenti jelölő négyzetben, hogy nem robot.',  'Avada');
      $this->returnJSON($return);
    }


    $to       = get_option('admin_email');
    $mail_subject  = sprintf(__('Új %s érkezett: %s'), $contact_type, $subject.' - '.$name);

    ob_start();
  	  include(locate_template('templates/mails/contactform.php'));
      $message = ob_get_contents();
		ob_end_clean();

    add_filter( 'wp_mail_from', array($this, 'getMailSender') );
    add_filter( 'wp_mail_from_name', array($this, 'getMailSenderName') );
    add_filter( 'wp_mail_content_type', array($this, 'getMailFormat') );

    $headers    = array();
    if (!empty($email)) {
      $headers[]  = 'Reply-To: '.$name.' <'.$email.'>';
    }

    $alert = wp_mail( $to, $mail_subject, $message, $headers );

    /* * /
    if (!empty($email)) {
      $headers    = array();
      $headers[]  = 'Reply-To: '.get_option('blogname').' <no-reply@'.TARGETDOMAIN.'>';
      $alerttext = true;
      ob_start();
    	  include(locate_template('templates/mails/contactform-receiveuser.php'));
        $message = ob_get_contents();
  		ob_end_clean();
      $ualert = wp_mail( $email, 'Értesítés: '.$contct_type.' üzenetét megkaptuk.', $message, $headers );
    }
    /* */

    if(!$alert) {
      $return['error']  = 1;
      $return['msg']    = sprintf(__('A(z) %s jelenleg nem tudtuk elküldeni. Próbálja meg később.',  'Avada'), $contact_type);
      $this->returnJSON($return);
    }

    echo json_encode($return);
    die();
  }

  public function AutocompleteCity()
  {
    global $wpdb;

    extract($_GET);

    $return = array();
    $arg    = array(
      'taxonomy' => 'utazas_uticel',
      'hierarchical' => 1,
      'hide_empty' => false,
      'orderby' => 'name',
      'order' => 'ASC'
    );

    if ($region) {
      //$arg['child_of'] = $region;
    }

    //$arg['name__like'] = $search;

    $terms = get_terms($arg);

    foreach ($terms as $t)
    {
      if ( true ) {
        $meta_query = array();

        $cqry = new WP_Query(array(
          'post_type'   => 'utazas',
          'meta_query'  => $meta_query,
          'tax_query' => array(
        		array(
        			'taxonomy' => 'utazas_uticel',
        			'field'    => 'id',
        			'terms'    => $t->term_id,
        		),
        	)
        ));

        $count = $cqry->found_posts;
        unset($cqry);

        // Ha nincs becsatolva utazás, akkor kihagyja
        //if( intval($count) === 0 ) continue;
      }

      // Ha nincs szülő item, akkor kihagyja
      if ($t->parent == 0) {
        //continue;
      }
      if ($t->parent != 0) {
        $parent = get_term($t->parent);
      }

      $name = $t->name;
      // --- $name = ( ($parent->slug == 'budapest') ? $parent->name.' / '.$t->name.' '.__('kerület') : $t->name  );

      if (!empty($search) && stristr($name, $search) === FALSE) {
        continue;
      }

      $return[] = array(
        'label' => $name,
        'value' => (int)$t->term_id,
        'slug' => $t->slug,
        'region_id' => $t->parent,
        'count' => $t->count,
        'region' => array(
          'name' => $parent->name,
          'slug' => $parent->slug,
          'parent' => $parent->parent
        )
      );
    }

    header('Content-Type: application/json;charset=utf8');
    echo json_encode($return);
    die();
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

  private function returnJSON($array)
  {
    echo json_encode($array);
    die();
  }

}
?>
