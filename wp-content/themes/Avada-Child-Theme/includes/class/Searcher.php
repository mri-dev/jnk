<?php
/**
 * Searcher for Travel
 */
class Searcher
{
  public $arg = array();
  public $pages = array(
    'current' => 1,
    'max' => 1
  );
  private $accepted_filters = array();

  public function __construct( $arg = array() )
  {
     $this->arg = array_replace( $this->arg, $arg );

     return $this;
  }

  public function Listing( $arg = array() )
  {
    global $post;
    $current_page = 1;
    $back = array();
    $filters = (array)$arg['filters'];

    $meta_query = array();
    $tax_query = array();

    $src = array();
    $src['post_type'] = 'utazas';

    $current_page = (!empty($arg['page'])) ? (int)$arg['page'] : $current_page;

    if ($filters['tag'] && !empty($filters['tag'])) {
      $src['tag_slug__in'] = $filters['tag'];
      $this->accepted_filters['tag'] = array(
        'value' => $filters['tag'],
        'label' => $this->getTermName('post_tag', $filters['tag'])
      );
    }

    if ($filters['kiemelt'] && !empty($filters['kiemelt'])) {
      $meta_query[] = array(
  			'key' => METAKEY_PREFIX.'kiemelt',
  			'value' => 1
  		);
    }

    if ($arg['ids'] && !empty($arg['ids'])) {
      $src['post__in'] = (array)$arg['ids'];
    }

    if ($arg['limit'] && !empty($arg['limit'])) {
      $src['posts_per_page'] = (int)$arg['limit'];
    }
    if ($arg['orderby'] && !empty($arg['orderby'])) {
      $src['orderby'] = $arg['orderby'];
    }

    if ($arg['tax_id'] && !empty($arg['tax_id'])) {
      $tax_query[] = array(
        'taxonomy' => 'utazas_kategoria',
        'field' => 'term_id',
        'terms' => $arg['tax_id']
      );
    }

    // Úti cél
    if ( isset($filters['cities']) && !empty($filters['cities']) )
    {
      $city = trim($filters['cities']);
      $cityterm = get_term_by('name', $city, 'utazas_uticel');

      if ($cityterm) {
        $tax_query[] = array(
          'taxonomy' => 'utazas_uticel',
          'field' => 'term_id',
          'terms' => array($cityterm->term_id)
        );
      }
    }

    // Keresés - tag alapján
    if ( isset($filters['search']) && !empty($filters['search']) )
    {
      $tagslugs = array();
      $keyword = explode(" ", trim($filters['search']));
      foreach ($keyword as $keyw) {
        $tagslugs[] = sanitize_title($keyw);
      }

      if (!empty($tagslugs)) {
        $src['tag_slug__in'] = $tagslugs;
      }
    }

    // Ellátás
    if ( isset($filters['el']) && !empty($filters['el']) )
    {
      $tax = explode(",", $filters['el']);
      if (!empty($tax)) {
        $tax_query[] = array(
          'taxonomy' => 'utazas_ellatas',
          'field' => 'term_id',
          'terms' => $tax
        );
        $this->accepted_filters['el'] = $this->prepareAcceptFilterRow($tax, 'utazas_ellatas', 'term_id');
      }
    }

    // Utazás hossza
    if ( isset($filters['dur']) && !empty($filters['dur']) )
    {
      $tax = explode(",", $filters['dur']);
      if (!empty($tax)) {
        $tax_query[] = array(
          'taxonomy' => 'utazas_duration',
          'field' => 'term_id',
          'terms' => $tax
        );
        $this->accepted_filters['dur'] = $this->prepareAcceptFilterRow($tax, 'utazas_duration', 'term_id');
      }
    }

    // Utazás kategória
    if ( isset($filters['type']) && !empty($filters['type'])) {
      $kat = trim($filters['type']);
      $tax_query[] = array(
        'taxonomy' => 'utazas_kategoria',
        'field' => 'slug',
        'terms' => $kat
      );
      $this->accepted_filters['type'] = array(
        'value' => $filters['type'],
        'label' => $this->getTermName('utazas_kategoria', $filters['type'])
      );
    }

    // Utazás módja
    if ( isset($filters['um']) && !empty($filters['um']) )
    {
      $tax = explode(",", $filters['um']);
      if (!empty($tax)) {
        $tax_query[] = array(
          'taxonomy' => 'utazas_mod',
          'field' => 'term_id',
          'terms' => $tax
        );
        $this->accepted_filters['um'] = $this->prepareAcceptFilterRow($tax, 'utazas_mod', 'term_id');
      }
    }

    // Szolgáltatások
    if ( isset($filters['szolg']) && !empty($filters['szolg']) )
    {
      $tax = explode(",", $filters['szolg']);
      if (!empty($tax)) {
        $tax_query[] = array(
          'taxonomy' => 'utazas_szolgaltatasok',
          'field' => 'term_id',
          'terms' => $tax
        );
        $this->accepted_filters['szolg'] = $this->prepareAcceptFilterRow($tax, 'utazas_szolgaltatasok', 'term_id');
      }
    }

    if ( !empty($meta_query) )
    {
      $src['meta_query'] = $meta_query;
    }

    if ( !empty($tax_query) )
    {
      $src['tax_query'] = $tax_query;
    }

    $src['paged'] = $current_page;

    $datas = new WP_Query( $src );

    $this->pages['current'] = $current_page;
    $this->pages['max'] = (int)$datas->max_num_pages;
    $this->pages['items'] = (int)$datas->found_posts;

    if ( $datas->have_posts() ) {
      while( $datas->have_posts() ) {
        $datas->the_post();

        $back[] = new Travel( $post );
      }
      wp_reset_postdata();
    }

    return $back;
  }

  public function prepareAcceptFilterRow( $set = array(), $what, $by = 'term_id' )
  {
    $back = array();
    foreach ( $set as $s ) {
      $back[] = array(
        'value' => $s,
        'label' => $this->getTermName( $what, $s, $by )
      );
    }

    return $back;
  }

  public function getTermName( $what, $val, $by = 'slug' )
  {
    $tag = get_term_by($by, $val, $what );

    $val = ($tag) ? $tag->name : $val;

    return $val;
  }

  public function acceptedFilters()
  {
    return $this->accepted_filters;
  }

  public function getSelectors( $id, $sel_values = array(), $arg = array() )
  {
    if (!$sel_values) {
      $sel_values = array();
    }
    $param = array(
      'taxonomy' => $id,
      'echo' => false
    );
    $param = array_merge($param, $arg);

    $terms = get_terms($param);

    $t = array();

    foreach ($terms as $term) {
      $term->selected = (in_array($term->term_id, $sel_values)) ? true : false;
      //$term->name = $this->i18n_taxonomy_values($term->name);
      $t[] = $term;
    }

    $sorted_terms = array();

    $this->sort_hiearchical_order_term($t, $sorted_terms);
    unset($t);
    unset($terms);

    //print_r($sorted_terms);

    return $sorted_terms;
  }

  private function sort_hiearchical_order_term( Array &$cats, Array &$into, $parentId = 0 )
  {
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        $this->sort_hiearchical_order_term($cats, $topCat->children, $topCat->term_id);
    }
  }

  public function pagination()
  {
    $href = '/utazas/';
    $param = array();
    unset($_GET['page']);
    $param = $_GET;
    $qry = build_query($param);
    if ( $qry == '') {
      $href .= '?';
    } else {
      $href .= '?'.$qry.'&';
    }

    $t = '<div class="pagination">';
      $t .= '<ul>';
      for( $p = 1; $p <= $this->pages[max]; $p++ ){
        $t .= '<li class="'. ( ($p == $this->pages[current])?'active':'' ) .'"><a href="'.$href.'page='.$p.'">'.$p.'</a></li>';
      }
      $t .= '</ul>';
    $t .= '</div>';

    return $t;
  }
}

?>
