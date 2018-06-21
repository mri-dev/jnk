<div class="sidebar">
  <?php
  global $post;
    $programs = get_posts(array(
      'post_type' => 'programok',
      'orderby' => 'rand',
      'posts_per_page' => 3,
      'post__not_in' => array($post->ID)
    ));
    wp_reset_postdata();
  ?>
  <?php if ($programs): ?>
  <div class="sidebar-group">
    <h3><?php echo __('Egyéb programok', TD); ?></h3>
    <div class="sidebar-programs">
      <?php foreach ($programs as $prog):
          $img = get_the_post_thumbnail_url($prog->ID);
        ?>
        <div class="program">
          <div class="wrapper">
            <div class="image">
              <a href="<?php echo get_the_permalink($prog->ID); ?>"><img src="<?=$img?>" alt="<?php echo $prog->post_title; ?>"></a>
            </div>
            <div class="data">
              <div class="title">
                <h3><a href="<?php echo get_the_permalink($prog->ID); ?>"><?php echo $prog->post_title; ?></a></h3>
              </div>
              <div class="sdesc">
                <?php echo get_the_excerpt($prog->ID); ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
