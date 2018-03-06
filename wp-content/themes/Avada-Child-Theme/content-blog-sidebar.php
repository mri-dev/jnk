<div class="sidebar article-sidebar">
  <?php
  global $post;
  $popularpost = new WP_Query( array(
    'posts_per_page' => 5,
    'meta_key' => 'jnk_post_views',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'  )
  );
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
              <i class="fa fa-columns"></i>
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
  <?php if ( function_exists( 'wp_tag_cloud' ) ) : ?>
  <?php
    $tags = wp_tag_cloud( array(
      'format' => 'array'
    ) );
    if( !empty($tags) ) {
  ?>
  <div class="tagclouds">
    <h3><?php echo __('Címkefelhő', TD); ?></h3>
    <div class="tags">
      <?php foreach ($tags as $tag): ?>
        <div class="tag">
          <?php echo $tag; ?>
        </div>
      <?php endforeach; ?>

    </div>
  </div>
  <?php } endif; ?>
</div>
