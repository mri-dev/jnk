<div class="sidebar article-sidebar">
  <?php
  global $post;
  $popularpost = new WP_Query( array(
    'posts_per_page' => 5,
    'meta_key' => 'jnk_post_views',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'  )
  );

  if (is_single())
  {
    // Kapcsolódó utazások kereső
    $tags = wp_get_post_tags($post->ID);

    if ($tags) {
      $tagids = array();
      foreach ( (array)$tags as $tag ) {
        $posts = new WP_Query(array(
          'post_type' => 'utazas',
          'tag__in' => $tag->term_id,
          'posts_per_page' => -1,
          'post__not_in' => array($post->ID)
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

      $rec_utazas = new Searcher();
      $arg = array();
      $arg['ids'] = $ids;
      $arg['limit'] = 3;
      $arg['orderby'] = 'post__in';
      $kapcs_utazas = $rec_utazas->Listing( $arg );
    }
  } else {
    $tags = wp_tag_cloud( array(
      'format' => 'array',
      'taxonomy' => 'post_tag'
    ) );
  }

  ?>
  <div class="recent-post">
    <h3><?php echo __('Népszerű bejegyzések', TD); ?></h3>
    <div class="popular-articles">
      <?php while ( $popularpost->have_posts() ) : $popularpost->the_post(); $cats = wp_get_post_categories($post->ID, array('fields' => 'all_with_object_id')); ?>
        <article class="">
          <div class="title">
            <h4><a href="<?php echo the_permalink(); ?>"><?php echo the_title(); ?></a></h4>
          </div>
          <div class="data">
            <div class="cats">
              <i class="far fa-folder"></i>
              <?php if ($cats): ?>
                <?php foreach ( $cats as $cat ): ?>
                  <span><a href="/category/<?=$cat->slug?>"><strong><?=$cat->name?></strong></a></span>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <div class="date">
              <?php echo get_the_date('Y. F j.'); ?>
            </div>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php if ($kapcs_utazas): ?>
  <div class="sidebar-group">
    <h3><?php echo __('Ajánlott utazások', TD); ?></h3>
    <div class="recommended-travels">
      <?php
      foreach ($kapcs_utazas as $u):
        $destionations = $u->showDestinations();
        $kiemelt = $u->isKiemelt();
        $discount = $u->getDiscount();
        $img = $u->Image();
        $hk = $u->getTags();
      ?>
      <div class="travel <?=($discount)?'discounted':''?>">
        <div class="wrapper">
          <div class="image">
            <?php if ($kiemelt): ?>
            <div class="kiemelt">
              <?php echo __('Kiemelt utazás', TD); ?>
            </div>
            <?php endif; ?>
            <a href="<?=$u->Url()?>"><img src="<?=$img?>" alt="<?=$u->Title()?>"></a>
          </div>
          <div class="data">
            <div class="title">
              <h3><a href="<?=$u->Url()?>" target="_blank"><?=$u->Title()?></a></h3>
            </div>
            <?php if (count($destionations) > 0): ?>
            <div class="position">
              <i class="fa fa-map-pin"></i>
              <?php if (count($destionations) > 5): ?>
                <span title="<?php echo implode(', ', $destionations); ?>"><?php echo sprintf(__('%d úti célt érint', TD), count($destionations)); ?></span>
              <?php else: ?>
                <?php echo implode(', ', $destionations); ?>
              <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="sdesc" title="<?php echo $u->getExcerpt(); ?>">
              <?php echo $u->getExcerpt(); ?>
            </div>
            <?php if ($hk): ?>
            <div class="keys">
              <?php foreach ($hk as $tag): ?>
                <a href="/utazas/?search=&tag=<?=$tag->slug?>">#<?=$tag->name?></a>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="price">
              <?php if ((int)$u->getPrice() !== 0): ?>
                <?php if ($discount): ?>
                <div class="old">
                  <?=number_format($u->getOriginalPrice(), 0, '', ' ')?> <?=get_valuta()?>
                </div>
                <?php endif; ?>
                <div class="current">
                  <?=number_format($u->getPrice(), 0, '', ' ')?> <?=get_valuta()?>
                </div>
              <?php else: ?>
                <div class="current">
                  Egyedi árazás
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
  <?php if ( function_exists( 'wp_tag_cloud' ) ) : ?>
  <?php
    if( !empty($tags) ) {
  ?>
  <div class="tagclouds">
    <h3><?php echo __('Címkefelhő', TD); ?></h3>
    <div class="tags">
      <?php foreach ($tags as $tag): ?>
        <div class="tag">
          <?php if (is_single()): ?>
            <a href="/tag/<?=$tag->slug?>"><?=$tag->name?></a>
          <?php else: ?>
          <?php echo $tag; ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php } endif; ?>
</div>
