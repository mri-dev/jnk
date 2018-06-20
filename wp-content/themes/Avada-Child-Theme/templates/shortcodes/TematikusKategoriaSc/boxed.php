<?php
  $img = get_the_post_thumbnail_url( $program->ID );
?>
<article class="program">
  <div class="wrapper">
    <a href="<?php echo get_post_permalink($program->ID); ?>">
      <div class="image">
        <img src="<?=$img?>" alt="<?php echo $program->post_title; ?>">
      </div>
      <div class="szalag">
        <div class="title">
          <?php echo $program->post_title; ?>
        </div>
        <div class="desc">
          <?php echo get_the_excerpt($program->ID); ?>
        </div>
      </div>
    </a>
  </div>
</article>
