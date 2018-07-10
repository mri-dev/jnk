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
    $image = get_the_post_thumbnail_url( $this->tpost );

    if ( $image ) {
      return $image;
    } else {
      return false;
    }
  }

  public function logView()
  {
    global $wpdb;

    $dg = date('Y-m-d');
    $cg = "SELECT viewcount FROM travel_term_view WHERE dategroup = '$dg' and post_id = '".$this->id."'";
    $vc = (int)$wpdb->get_var( $cg );

    if ($vc == 0) {
      $wpdb->insert(
      	'travel_term_view',
      	array(
      		'post_id' => $this->id,
      		'dategroup' => $dg,
          'viewcount' => 1
      	),
      	array(
      		'%d',
      		'%s',
          '%d'
      	)
      );
    } else {
      $wpdb->update(
        'travel_term_view',
        array(
          'viewcount' => $vc + 1
        ),
        array(
          'post_id' => $this->id,
          'dategroup' => $dg
        ),
        array('%d'),
        array('%d', '%s')
      );
    }
  }

  public function getRecommendedPostsIDSByTags( $tags = array() )
  {
    $ids = array();

    if (empty($tags)) {
      return false;
    }

    $tagids = array();
    foreach ( (array)$tags as $tag ) {
      $posts = new WP_Query(array(
        'post_type' => 'post',
        'tag__in' => $tag->term_id,
        'posts_per_page' => -1
      ));

      if ($posts->have_posts()) {
        while ( $posts->have_posts() ) {
          $posts->the_post();
          $tagids[get_the_ID()]['postid'] = get_the_ID();
          $tagids[get_the_ID()]['tn'] += 1;
          $tagids[get_the_ID()]['tags'][] = $tag->name;
        }
        wp_reset_postdata();
      }
    }
    usort($tagids, function ($item1, $item2) {
      if ($item1['tn'] == $item2['tn']) return 0;
      return $item1['tn'] < $item2['tn'] ? 1 : -1;
    });

    foreach ( (array)$tagids as $ti ) {
      $ids[] = $ti['postid'];
    }
    unset($tagids);
    unset($posts);

    return $ids;
  }

  public function getRecommendedTravelIDS( $tags = array() )
  {
    if ( empty($tags) )
    {
      $ids = explode(",",get_post_meta($this->id, METAKEY_PREFIX . 'ajanlatok', true));
    } else {
      $tagids = array();
      foreach ( (array)$tags as $tag ) {
        $posts = new WP_Query(array(
          'post_type' => 'utazas',
          'tag__in' => $tag->term_id,
          'posts_per_page' => -1,
          'post__not_in' => array($this->id)
        ));

        if ($posts->have_posts()) {
          while ( $posts->have_posts() ) {
            $posts->the_post();
            $tagids[get_the_ID()]['postid'] = get_the_ID();
            $tagids[get_the_ID()]['tn'] += 1;
            $tagids[get_the_ID()]['tags'][] = $tag->name;
        	}
          wp_reset_postdata();
        }
      }
      usort($tagids, function ($item1, $item2) {
        if ($item1['tn'] == $item2['tn']) return 0;
        return $item1['tn'] < $item2['tn'] ? 1 : -1;
      });

      foreach ( (array)$tagids as $ti ) {
        $ids[] = $ti['postid'];
      }
      unset($tagids);
      unset($posts);
    }

    return $ids;
  }

  public function getPrograms()
  {
    $programs = array();
    $ids = explode(",",get_post_meta($this->id, METAKEY_PREFIX . 'programok', true));

    $programs = get_posts(array(
      'post_type' => 'programok',
      'post__in' => $ids
    ));

    return $programs;
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

  public function maxBefogadas()
  {
    $v = (int)get_post_meta($this->id, METAKEY_PREFIX . 'max_befogadas', true);

    return ($v == 0) ? false : $v;
  }

  public function getGalleryID()
  {
    $v = (int)get_post_meta($this->id, METAKEY_PREFIX . 'photo_gallery_id', true);

    return ($v == 0) ? false : $v;
  }

  public function getTags()
  {
    $v = wp_get_post_tags($this->id);

    return $v;
  }

  public function isEgyeni()
  {
    $egyeni_utazas = get_post_meta($this->id, METAKEY_PREFIX . 'egyeni_utazas', true);

    return (empty($egyeni_utazas)) ? false : true;
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

  public function getExcerpt()
  {
    $v = get_the_excerpt($this->id);

    return $v;
  }

  public function getHotelStars()
  {
    $type = $this->getHotelType();

    switch ($type->name) {
      case '1 csillag': case '2 csillag': case '3 csillag': case '4 csillag': case '5 csillag':
        $star = (int)trim(str_replace(" csillag","", $type->name));
        return $star;
      break;

      default:
        return false;
      break;
    }
  }

  public function getHotelType()
  {
    $terms = wp_get_post_terms($this->id, array(
      'taxonomy' => 'hotel_kategoria'
    ));

    return $terms[0];
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

  public function getTestimonials()
  {
    $data = array();
    $meta_query = array();
    $tax_query = array();

    $meta_query[] = array(
      'key' => 'travel_id',
      'value' => $this->id
    );

    $tax_query[] = array(
      'taxonomy' => 'wpm-testimonial-category',
      'field' => 'term_id',
      'terms' => 22
    );

    $qry = get_posts(array(
      'post_type' => 'wpm-testimonial',
      'posts_per_page' => -1,
      'orderby' => 'rand',
      'meta_query' => $meta_query,
      'tax_query' => $tax_query
    ));

    foreach ((array)$qry as $d) {
      $d->client_name = get_post_meta( $d->ID, 'client_name', true);
      $d->destination = get_post_meta( $d->ID, 'destination', true);
      $data[] = $d;
    }


    return $data;
  }

  public function __destruct()
  {
    $this->arg = null;
  }
}

?>
