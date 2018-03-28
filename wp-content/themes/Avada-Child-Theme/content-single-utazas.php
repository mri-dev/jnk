<?php global $travel; ?>
<div class="travel-base-wrapper">
  <div class="travel-header">
    <div class="page-width">
      HEADER
    </div>
  </div>
  <div class="travel-nav">
    <div class="page-width">
      NAV
    </div>
  </div>
  <div class="base-content-holder">
    <div class="travel-contents">
      <div class="description">
        <a name="description"></a>
        <?php the_content(); ?>
      </div>
    </div>
    <div class="sidebar">
      <?php get_template_part( 'content', 'utazas-sidebar' );  ?>
    </div>
  </div>
</div>
