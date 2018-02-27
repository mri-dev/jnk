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
