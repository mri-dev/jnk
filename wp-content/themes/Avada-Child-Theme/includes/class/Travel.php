<?php
/**
 * Travel class for Searcher items
 */
class Travel
{
  public $id = null;
  private $tpost = null;
  private $data;
  public $arg = array();

  public function __construct( \WP_Post $travel, $arg = array() )
  {
     $this->arg = array_replace( $this->arg, $arg );

     $this->id = $travel->ID;
     $this->tpost = $travel;

     return $this;
  }

  public function Title()
  {
    return $this->tpost->post_title;
  }

  public function Image()
  {
    return get_the_post_thumbnail_url( $this->tpost );
  }

  public function Url()
  {
    return get_post_permalink( $this->tpost );
  }

  public function isKiemelt()
  {
    $kiemelt = (int)get_post_meta($this->id, METAKEY_PREFIX . 'kiemelt', true);

    if ( $kiemelt == 0 ) {
      return false;
    } else {
      return true;
    }
  }

  public function getOriginalPrice()
  {
    $v = get_post_meta($this->id, METAKEY_PREFIX . 'ar', true);

    return $v;
  }

  public function getTravelFrom()
  {
    $v = get_post_meta($this->id, METAKEY_PREFIX . 'travel_from', true);

    return $v;
  }

  public function getPriceComment()
  {
    $v = get_post_meta($this->id, METAKEY_PREFIX . 'ar_magyarazat', true);

    return $v;
  }

  public function getTravelTo()
  {
    $v = get_post_meta($this->id, METAKEY_PREFIX . 'travel_to', true);

    return $v;
  }

  public function getDiscountPrice()
  {
    $v = get_post_meta($this->id, METAKEY_PREFIX . 'ar_akcios', true);

    return $v;
  }

  public function getPrice()
  {
    if ( $this->getDiscount() ) {
      $v = get_post_meta($this->id, METAKEY_PREFIX . 'ar_akcios', true);
    } else {
      $v = get_post_meta($this->id, METAKEY_PREFIX . 'ar', true);
    }

    return $v;
  }

  public function getDiscount()
  {
    $discount = false;

    $disc_price = $this->getDiscountPrice();

    if ( $disc_price )
    {
      $dp = $this->getDiscountPrice();
      $p = $this->getOriginalPrice();

      $sze = $p - $dp;

      $percent = ($sze/$p) * 100;

      $discount = array(
        'percent' => round($percent),
        'price' => round($p-$dp)
      );
    }

    return $discount;
  }

  public function showDuration()
  {
    $back = array();

    $raw_terms = $this->getDurations();

    foreach ($raw_terms as $t) {
      $back[] = $t['name'];
    }

    return $back;
  }

  public function showDestinations()
  {
    $back = array();

    $raw_terms = $this->getDestinations();

    foreach ($raw_terms as $t) {
      $back[] = $t['name'];
    }

    return $back;
  }

  public function showUtazasMod()
  {
    $back = array();

    $raw_terms = $this->getTermValues('utazas_mod');

    foreach ($raw_terms as $t) {
      $back[] = $t['name'];
    }

    return $back;
  }

  public function showEllatas()
  {
    $back = array();

    $raw_terms = $this->getTermValues('utazas_ellatas');

    foreach ($raw_terms as $t) {
      $back[] = $t['name'];
    }

    return $back;
  }

  public function getDurations()
  {
    $list = array();

    $terms = wp_get_post_terms($this->id, array(
      'taxonomy' => 'utazas_duration'
    ));

    foreach ((array)$terms as $t) {
      $list[] = array(
        'name' => $t->name,
        'obj' => $t
      );
    }

    return $list;
  }

  private function getTermValues( $term = false )
  {
    $list = array();

    $terms = wp_get_post_terms($this->id, array(
      'taxonomy' => $term
    ));

    foreach ((array)$terms as $t) {
      $list[] = array(
        'name' => $t->name,
        'obj' => $t
      );
    }

    return $list;
  }

  public function getDestinations( $include_parent = false )
  {
    $list = array();
    $parents_cnt = 0;
    $child_cnt = 0;
    $terms = wp_get_post_terms($this->id, array(
      'taxonomy' => 'utazas_uticel'
    ));

    foreach ((array)$terms as $t) {
      if($t->parent == 0) $parents_cnt++;
      if($t->parent != 0) $child_cnt++;
    }

    // Ha csak a legfelsőbb szintű úti célok vannak és nincsenek városok
    if ( $parents_cnt != 0 && $child_cnt == 0 )
    {
      foreach ((array)$terms as $t) {
        $list[] = array(
          'name' => $t->name,
          'obj' => $t
        );
      }
    }
    // Ha csak alsóbb szintű term és nincs szülő megjelölve
    else if ( $parents_cnt == 0 && $child_cnt != 0 )
    {
      foreach ((array)$terms as $t) {
        $t->parentrow = $this->traceTermParent($t);
        $list[] = array(
          'name' => $t->name,
          'obj' => $t
        );
      }
    }
    // Ha vegyesen van szülő és gyermek, akkor  csak a gyermek listázása
    else if ( $parents_cnt != 0 && $child_cnt != 0 ) {
      foreach ((array)$terms as $t) {
        if($t->parent == 0) continue;
        $t->parentrow = $this->traceTermParent($t);
        $list[] = array(
          'name' => $t->name,
          'obj' => $t
        );
      }
    }

    return $list;
  }

  private function traceTermParent( \WP_Term $term )
  {
    $list = array();
    $parent = (int)$term->parent;
    $current = $term;

    while ( $parent !== 0 ) {
      $parent = (int)$current->parent;
      if ($parent !== 0) {
        $current = get_term($parent, 'utazas_uticel');
        $list[] = $current;
      }
    }

    return $list;
  }

  public function __destruct()
  {
    $this->arg = null;
  }
}

?>
