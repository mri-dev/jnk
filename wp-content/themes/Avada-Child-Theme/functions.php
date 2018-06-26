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
define('UTAZAS_SLUG', 'utazas');

// Includes
//require_once WP_PLUGIN_DIR."/cmb2/init.php";
require_once "includes/include.php";

function get_valuta()
{
  return 'Ft';
}

function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css?' . ( (DEVMODE === true) ? time() : '' )  );
    wp_enqueue_style( 'avada-child-stylesheet', IFROOT . '/style.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_style( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css', array(), '1.12.1' );
    wp_enqueue_style( 'angular-material','//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.css');
    wp_enqueue_style( 'angualardatepick', IFROOT . '/assets/vendor/md-date-range-picker/md-date-range-picker.min.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    //wp_enqueue_style( 'fontawesome', VENDORS . '/font-awesome-'.FONTAWESOME_VERSION.'/css/font-awesome.min.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    //wp_enqueue_style( 'slick', IFROOT . '/assets/vendor/slick/slick.css' );
    //wp_enqueue_style( 'slick-theme', IFROOT . '/assets/css/slick-theme.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
    //wp_enqueue_script( 'slick', IFROOT . '/assets/vendor/slick/slick.min.js', array('jquery'));

    wp_enqueue_script( 'recaptcha', '//www.google.com/recaptcha/api.js');
    wp_enqueue_script( 'jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'), '1.12.1');
    wp_enqueue_script( 'jquery-ui-loc-hu', IFROOT . '/assets/js/jquery-ui-loc-hu.js');
    wp_enqueue_script( 'fontasesome', '//use.fontawesome.com/releases/v5.0.6/js/all.js');
    //wp_enqueue_script( 'google-maps', '//maps.googleapis.com/maps/api/js?language=hu&region=hu&key='.GOOGLE_API_KEY);
    wp_enqueue_script( 'angularjs', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js');
    wp_enqueue_script( 'angular-animate', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js');
    wp_enqueue_script( 'angular-aria', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js');
    wp_enqueue_script( 'angular-message', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js');
    wp_enqueue_script( 'angular-material', '//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js');
    wp_enqueue_script( 'mocjax', IFROOT . '/assets/vendor/autocomplete/scripts/jquery.mockjax.js');
    wp_enqueue_script( 'autocomplete', IFROOT . '/assets/vendor/autocomplete/dist/jquery.autocomplete.min.js');
    wp_enqueue_script( 'angualardatepick', IFROOT . '/assets/vendor/md-date-range-picker/md-date-range-picker.js?t=' . ( (DEVMODE === true) ? time() : '' ) );
    wp_enqueue_script( 'angualarjnk', IFROOT . '/assets/js/app.js?t=' . ( (DEVMODE === true) ? time() : '' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function jnk_load_scripts($hook) {

	if( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) return;
  wp_enqueue_style( 'jnkadmin', IFROOT . '/assets/css/admin.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
  wp_enqueue_style( 'angular-material','//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.css');

  // Angular & Material Design
  wp_enqueue_script( 'angularjs', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js');
  wp_enqueue_script( 'angular-animate', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js');
  wp_enqueue_script( 'angular-aria', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js');
  wp_enqueue_script( 'angular-message', '//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js');
  wp_enqueue_script( 'angular-material', '//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js');
	wp_enqueue_script( 'angualarjnk', IFROOT . '/assets/js/app.js?t=' . ( (DEVMODE === true) ? time() : '' ) );
}
add_action('admin_enqueue_scripts', 'jnk_load_scripts');

function utazas_durration_tax_metas()
{
  ?>
  	<div class="form-field">
  		<label for="nights"><?php _e( 'Éjszakák száma' ); ?></label>
  		<input type="number" min="1" step="1" name="nights" id="nights" value="">
  	</div>
  <?php
}
add_action( 'utazas_duration_add_form_fields', 'utazas_durration_tax_metas', 10, 2 );

function utazas_durration_tax_metas_edit($term)
{
  $t_id = $term->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );
  ?>
  	<tr class="form-field">
	    <th scope="row" valign="top"><label for="nights"><?php _e( 'Éjszakák száma', TD ); ?></label></th>
  		<td>
  			<input type="number" min="1" step="1" name="nights" id="nights" value="<?php echo esc_attr( $term_meta['nights'] ) ? esc_attr( $term_meta['nights'] ) : ''; ?>">
  		</td>
  	</tr>
  <?php
}
add_action( 'utazas_duration_edit_form_fields', 'utazas_durration_tax_metas_edit', 10, 2 );

function save_taxonomy_custom_meta( $term_id )
{
  if (isset($_POST['nights'])) {
    $t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
    $term_meta['nights'] = $_POST['nights'];
    update_option( "taxonomy_$t_id", $term_meta );
  }
}
add_action( 'edited_utazas_duration', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_utazas_duration', 'save_taxonomy_custom_meta', 10, 2 );

function utazas_duration_taxonomy_columns( $columns )
{
	$columns['nights'] = __('Éjszakák száma', TD);

	return $columns;
}
add_filter('manage_edit-utazas_duration_columns' , 'utazas_duration_taxonomy_columns');

function utazas_duration_taxonomy_columns_content( $content, $column_name, $term_id )
{
    if ( 'nights' == $column_name ) {
      $term_meta = get_option( "taxonomy_$term_id" );
      $content = $term_meta['nights'];
    }
	return $content;
}
add_filter( 'manage_utazas_duration_custom_column', 'utazas_duration_taxonomy_columns_content', 10, 3 );

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

function app_query_vars($vars){
  $vars[] = 'custom_page';
  $vars[] = 'postid';
  $vars[] = 'travel_group';
  $vars[] = 'travel_mode';
  $vars[] = 'travel_termid';
  return $vars;
}
add_filter('query_vars', 'app_query_vars', 0, 1);

// Admin menü
//add_filter( 'admin_footer_text', '__return_empty_string', 11 );
//add_filter( 'update_footer', '__return_empty_string', 11 );

function jk_init()
{
  date_default_timezone_set('Europe/Budapest');
  add_post_type_support( 'page', 'excerpt' );

  add_rewrite_rule(
    '^travelmodalconfig/([0-9]+)/([^/]*)/([^/]*)/([0-9]+)?',
    'index.php?custom_page=travelmodalconfig&postid=$matches[1]&travel_group=$matches[2]&travel_mode=$matches[3]&travel_termid=$matches[4]',
    'top'
  );

  create_custom_posttypes();
}
add_action('init', 'jk_init');

function app_custom_template($template) {
  global $post, $wp_query;

  if(isset($wp_query->query_vars['custom_page'])) {
    return get_stylesheet_directory() . '/'.$wp_query->query_vars['custom_page'].'.php';
  } else {
    return $template;
  }
}
add_filter( 'template_include', 'app_custom_template' );

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

  $utak->add_taxonomy( 'utazas_ellatas', array(
    'rewrite' => 'ellatas',
    'name' => array('Ellátás', 'Ellátások'),
    'labels' => array(
      'menu_name' => 'Ellátások',
      'add_new_item' => 'Új %s',
      'search_items' => '%s keresése',
      'all_items' => '%s',
    )
  ) );

  $utak->add_taxonomy( 'utazas_mod', array(
    'rewrite' => 'utazas-mod',
    'name' => array('Utazás mód', 'Utazás módok'),
    'labels' => array(
      'menu_name' => 'Utazás módok',
      'add_new_item' => 'Új %s',
      'search_items' => '%s keresése',
      'all_items' => '%s',
    )
  ) );

  $utak->add_taxonomy( 'utazas_szolgaltatasok', array(
    'rewrite' => 'szolgaltatasok',
    'name' => array('Szolgáltatás', 'Szolgáltatások'),
    'labels' => array(
      'menu_name' => 'Szolgáltatások',
      'add_new_item' => 'Új %s',
      'search_items' => '%s keresése',
      'all_items' => '%s',
    )
  ) );

  $utak_metabox = new CustomMetabox(
    'utazas',
    __('Utazás alapadatok', TD),
    new UtazasMetaboxSave(),
    'utazas',
    array(
      'class' => 'utazasalap-postbox'
    )
  );

  $editor_metabox = new CustomMetabox(
    'utazas',
    __('Utazás beállításai', TD),
    new TravelCalcEditorMetaboxSave(),
    'utazaseditor',
    array(
      'class' => 'utazaseditor-postbox'
    )
  );

	$utak->create();
  add_post_type_support( 'utazas', 'excerpt' );

  // Programok
  $programok = new PostTypeFactory( 'programok' );
	$programok->set_textdomain( TD );
	$programok->set_icon('tag');
	$programok->set_name( 'Program', 'Programok' );
	$programok->set_labels( array(
		'add_new' => 'Új %s',
		'not_found_in_trash' => 'Nincsenek %s a lomtárban.',
		'not_found' => 'Nincsenek %s a listában.',
		'add_new_item' => 'Új %s létrehozása',
	) );
  $programok->create();
  add_post_type_support( 'programok', 'excerpt' );
}

/**
* AJAX REQUESTS
*/
function ajax_requests()
{
  $ajax = new AjaxRequests();
  $ajax->city_autocomplete();
  $ajax->contact_form();
  $ajax->travel_api();
  $ajax->getterms();
  $ajax->traveler();
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
  table.jnk{
    width: 100%;
  }
  table.jnk td{
    padding: 10px;
    vertical-align: top;
  }
  table.jnk input[type=text],
  table.jnk select{
    width: 100%;
    padding: 8px;
    height: auto;
  }
  </style>';
}
add_action('admin_head', 'custom_admin_css');

function jnk_comment($comment, $args, $depth) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }?>
    <<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>"><?php
    if ( 'div' != $args['style'] ) { ?>
        <div id="div-comment-<?php comment_ID() ?>" class="the-comment"><?php
    } ?>
        <div class="avatar">
          <?php
              if ( $args['avatar_size'] != 0 ) {
                  echo get_avatar( $comment, 54 );
              }
          ?>
        </div>
        <div class="comment-box">
          <div class="comment-author meta">
            <?php
              $args['reply_text'] = __('Reply').' <i class="fas fa-reply" data-fa-transform="flip-h"></i>';
              comment_reply_link(
                  array_merge(
                      $args,
                      array(
                          'add_below' => $add_below,
                          'depth'     => $depth,
                          'max_depth' => $args['max_depth']
                      )
                  )
              );
            ?>
            <strong><?php printf( __( '%s' ), get_comment_author_link() ); ?></strong>
            <div class="date">
              <?php
                /* translators: 1: date, 2: time */
                printf(
                    '%s, %s',
                    get_comment_date('Y. F j.'),
                    get_comment_time()
                );
              ?>
            </div>
          </div>
          <div class="comment-text">
            <?php comment_text(); ?>
          </div>
          <div class="comment-edit">
            <?php edit_comment_link( __( 'Edit' ), '<i class="fas fa-pencil-alt"></i>', '' ); ?>
          </div>
        </div>
        <?php
    if ( 'div' != $args['style'] ) : ?>
        </div><?php
    endif;
}
