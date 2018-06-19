<?php
/**
 * Searcher for Travel
 */
class Searcher
{
  public $arg = array();

  public function __construct( $arg = array() )
  {
     $this->arg = array_replace( $this->arg, $arg );

     return $this;
  }

  public function Listing( $arg = array() )
  {
    global $post;
    $back = array();
    $filters = (array)$arg['filters'];

    $src = array();
    $src['post_type'] = 'utazas';

    if ($filters['tag'] && !empty($filters['tag'])) {
        $src['tag_slug__in'] = $filters['tag'];
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

    $datas = new WP_Query( $src );

    if ( $datas->have_posts() ) {
      while( $datas->have_posts() ) {
        $datas->the_post();

        $back[] = new Travel( $post );
      }
      wp_reset_postdata();
    }

    return $back;
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
}

?>
