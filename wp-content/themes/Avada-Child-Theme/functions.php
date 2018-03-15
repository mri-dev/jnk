<?php
define('IFROOT', get_stylesheet_directory_uri());
define('DEVMODE', true);
define('IMG', IFROOT.'/images');
define('GOOGLE_API_KEY', 'AIzaSyA0Mu8_XYUGo9iXhoenj7HTPBIfS2jDU2E');
define('VENDORS', IFROOT.'/assets/vendor');
define('FONTAWESOME_VERSION', '4.7.0');
define('TD', 'jnk'); // Textdomain
define('METAKEY_PREFIX', 'jnk_'); // Textdomain
define('CAPTCHA_SITE_KEY', '6LcUGUoUAAAAAIjFl--yWShSlAHTOBKLUZwpXXeV');
define('CAPTCHA_SECRET_KEY', '6LcUGUoUAAAAAEmnJEAqm3Ks5X_HodUkw5vIn0C0');

// Includes
//require_once WP_PLUGIN_DIR."/cmb2/init.php";
require_once "includes/include.php";

function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css?' . ( (DEVMODE === true) ? time() : '' )  );
    wp_enqueue_style( 'avada-child-stylesheet', IFROOT . '/style.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', array(), '1.12.1' );
    //wp_enqueue_style( 'fontawesome', VENDORS . '/font-awesome-'.FONTAWESOME_VERSION.'/css/font-awesome.min.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    //wp_enqueue_style( 'slick', IFROOT . '/assets/vendor/slick/slick.css' );
    //wp_enqueue_style( 'slick-theme', IFROOT . '/assets/css/slick-theme.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    //wp_enqueue_script( 'slick', IFROOT . '/assets/vendor/slick/slick.min.js', array('jquery'));

    wp_enqueue_script( 'recaptcha', '//www.google.com/recaptcha/api.js');
    wp_enqueue_script( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), '1.12.1');
    wp_enqueue_script( 'jquery-ui-loc-hu', IFROOT . '/assets/js/jquery-ui-loc-hu.js');
    wp_enqueue_script( 'fontasesome', '//use.fontawesome.com/releases/v5.0.6/js/all.js');
    //wp_enqueue_script( 'google-maps', '//maps.googleapis.com/maps/api/js?language=hu&region=hu&key='.GOOGLE_API_KEY);
    //wp_enqueue_script( 'angularjs', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js');
    wp_enqueue_script( 'mocjax', IFROOT . '/assets/vendor/autocomplete/scripts/jquery.mockjax.js');
    wp_enqueue_script( 'autocomplete', IFROOT . '/assets/vendor/autocomplete/dist/jquery.autocomplete.min.js');

}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function custom_theme_enqueue_styles() {
    wp_enqueue_style( 'krakko-base', IFROOT . '/assets/css/base.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    //wp_enqueue_script( 'krakko', IFROOT . '/assets/js/master.js?t=' . ( (DEVMODE === true) ? time() : '' ), array('jquery'), '', 999 );
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_styles', 100 );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
  $ucid = ucid();
  $ucid = $_COOKIE['uid'];
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

function ucid()
{
  $ucid = $_COOKIE['ucid'];

  if (!isset($ucid)) {
    $ucid = mt_rand();
    setcookie( 'ucid', $ucid, time() + 60*60*24*365*2, "/");
  }

  return $ucid;
}

// Admin menü
//add_filter( 'admin_footer_text', '__return_empty_string', 11 );
//add_filter( 'update_footer', '__return_empty_string', 11 );

function jk_init()
{
  date_default_timezone_set('Europe/Budapest');
  add_post_type_support( 'page', 'excerpt' );

  create_custom_posttypes();
}
add_action('init', 'jk_init');

function create_custom_posttypes()
{
  // Utak
  $utak = new PostTypeFactory( 'utazas' );
	$utak->set_textdomain( TD );
	$utak->set_icon('tag');
	$utak->set_name( 'Utazás', 'Utazások' );
	$utak->set_labels( array(
		'add_new' => 'Új %s',
		'not_found_in_trash' => 'Nincsenek %s a lomtárban.',
		'not_found' => 'Nincsenek %s a listában.',
		'add_new_item' => 'Új %s létrehozása',
	) );

  // Utazás hosszak
  $utak->add_taxonomy( 'utazas_duration', array(
		'rewrite' => 'utazas-hossz',
		'name' => array('Utazás hossz', 'Utazások hossza'),
		'labels' => array(
			'menu_name' => 'Utazások hossza',
			'add_new_item' => 'Új %s',
			'search_items' => '%s keresése',
			'all_items' => '%s',
		)
	) );

  // Utazás úti célok
  $utak->add_taxonomy( 'utazas_uticel', array(
    'rewrite' => 'utazas-uticel',
    'name' => array('Úti cél', 'Úti célok'),
    'labels' => array(
      'menu_name' => 'Úti célok',
      'add_new_item' => 'Új %s',
      'search_items' => '%s keresése',
      'all_items' => '%s',
    )
  ) );

  $utak->add_taxonomy( 'utazas_kategoria', array(
    'rewrite' => 'utazas-kategoria',
    'name' => array('Kategória', 'Kategóriák'),
    'labels' => array(
      'menu_name' => 'Kategóriák',
      'add_new_item' => 'Új %s',
      'search_items' => '%s keresése',
      'all_items' => '%s',
    )
  ) );

  /*$utak_metabox = new CustomMetabox(
    'utazas',
    __('Utazás beállítások', TD),
    new UtazasMetaboxSave(),
    'utazas'
  );*/

	$utak->create();
  add_post_type_support( 'utazas', 'excerpt' );
}

/**
* AJAX REQUESTS
*/
function ajax_requests()
{
  $ajax = new AjaxRequests();
  $ajax->city_autocomplete();
  $ajax->contact_form();
}
add_action( 'init', 'ajax_requests' );

// AJAX URL
function get_ajax_url( $function )
{
  return admin_url('admin-ajax.php?action='.$function);
}

function auto_update_post_meta( $post_id, $field_name, $value = '' )
{
    if ( empty( $value ) OR ! $value )
    {
      delete_post_meta( $post_id, $field_name );
    }
    elseif ( ! get_post_meta( $post_id, $field_name ) )
    {
      add_post_meta( $post_id, $field_name, $value );
    }
    else
    {
      update_post_meta( $post_id, $field_name, $value );
    }
}

function jnk_track_post_views ( $post_id )
{
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    jnk_set_post_views($post_id);
}
add_action( 'wp_head', 'jnk_track_post_views');

function jnk_set_post_views( $postID )
{
    $count_key = 'jnk_post_views';
    $count = get_post_meta($postID, $count_key, true);

    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function custom_admin_css() {
  echo '<style>
    .wp-picker-container{
	position:relative !important;
    }
    .wp-picker-container > .wp-color-result{
	display: inline-block;
	height: 32px;
	min-width: 32px;
	border: 1px #ccc solid;
	}

	.wp-color-picker {
	display: inline !important;
	}

	.wp-picker-default {
	display: inline !important;
	float: right;
	margin: 0;
	}
  </style>';
}
add_action('admin_head', 'custom_admin_css');
