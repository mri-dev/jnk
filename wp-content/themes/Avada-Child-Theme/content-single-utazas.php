<?php global $travel; ?>
<div class="travel-base-wrapper">
  <div class="travel-header">
    <div class="page-width">
      <div class="fake-wrapper">
        <div class="fake-inside">
          HEADER
        </div>
      </div>
    </div>
  </div>
  <div class="travel-nav" id="fixnav">
    <div class="page-width">
      <div class="fake-wrapper">
        <div class="fake-inside">
          <ul>
            <li class="active"><a href="#top"><?php echo __('Adatok', TD); ?></a></li>
            <li><a href="#description"><?php echo __('Ismertető', TD); ?></a></li>
            <li><a href="#programs"><?php echo __('Programok', TD); ?></a></li>
            <li><a href="#galery"><?php echo __('Képek', TD); ?></a></li>
            <li><a href="#reviews"><?php echo __('Értékelések', TD); ?></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="base-content-holder">
    <div class="travel-contents">
      <div class="description">
        <a name="description"></a>
        <h2><i class="far fa-file-alt"></i> <?php echo __('Utazás ismertetése', TD); ?></h2>
        <?php the_content(); ?>
      </div>
    </div>
    <div class="sidebar">
      <?php get_template_part( 'content', 'utazas-sidebar' );  ?>
    </div>
  </div>
</div>
<script type="text/javascript">
  (function($){
    $(window).scroll(function(){
      trackFixElement();
    });

    function trackFixElement() {
      var navbarpos = $('.travel-header').offset().top + $('.travel-header').height()+80;
      var sc = $(document).scrollTop();
      var fixed = ( navbarpos < (sc+60) ) ? true : false;

      if (fixed) {
        $('.travel-base-wrapper').addClass('fixedelements');
      } else if( !fixed) {
        $('.travel-base-wrapper').removeClass('fixedelements');
      }

      var sidebaroffset = $('.travel-header').height() + 80 + $('.travel-nav').height() + 35 + $('.sidebar-header').height() +$('.utazas-sidebar .discount').height();
      $('.sidebar-fix-holder').css({
        top: (sidebaroffset * -1)
      });

      console.log(sidebaroffset);
    }
  })(jQuery);
</script>
