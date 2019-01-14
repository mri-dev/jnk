<?php get_header(); ?>
<div id="content" class="full-width" style="width: 100%;">
  <h2></h2>
  <h1 style="text-align: center;" data-fontsize="24" data-lineheight="33"><span style="color: #000000;"><?php echo __('Programok', 'jnk'); ?></span></h1>
  <?php if ( have_posts() ) : ?>
    <div class="programs">
      <?php
        // Start the Loop.
        while ( have_posts() ) : the_post();
          get_template_part( 'content', 'programlist' );
        endwhile;
        // Previous/next page navigation.
      ?>
    </div>
    <script type="text/javascript">
      (function($){
        var tviw = $('.programs .program .image').width();
        $('.programs .program .image').css({
          height: (tviw / 4 * 3)
        });
      })(jQuery);
    </script>
  <?php
    else :
      get_template_part( 'content', 'none' );
    endif;

    wp_reset_postdata();
  ?>
</div>
<?php get_footer();
