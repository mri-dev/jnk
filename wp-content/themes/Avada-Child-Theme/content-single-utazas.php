<?php global $travel; ?>
<?php
  $durations = $travel->showDuration();
?>
<a name="datas"></a>
<div class="travel-base-wrapper">
  <div class="travel-header">
    <div class="page-width">
      <div class="fake-wrapper">
        <div class="fake-inside">
          <div class="travel-main-params">
            <div class="durrations">
              <i class="far fa-clock"></i>
              <?php echo implode(', ', $durations); ?>
            </div>
            <div class="people-restricts">
              <i class="fas fa-users"></i>
              3 - 24 fő
            </div>
            <div class="travel-from">
              <i class="fas fa-sign-out-alt" data-fa-transform="rotate--45"></i>
              Budapest
            </div>
            <div class="travel-to">
              <i class="fas fa-sign-in-alt" data-fa-transform="rotate-45"></i>
              Krakkó
            </div>
            <div class="ellatas">
              <i class="fas fa-utensils"></i>
              Önellátás
            </div>
            <div class="travel-by">
              <i class="fas fa-car"></i>
              Egyéni
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="travel-nav" id="fixnav">
    <div class="page-width">
      <div class="fake-wrapper">
        <div class="fake-inside">
          <ul>
            <li class="active datas"><a href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('Adatok', TD); ?></a></li>
            <li class="description"><a href="javascript:void(0);" data-scrollTarget="description"><?php echo __('Ismertető', TD); ?></a></li>
            <li class="programs"><a href="javascript:void(0);" data-scrollTarget="programs"><?php echo __('Programok', TD); ?></a></li>
            <li class="gallery"><a href="javascript:void(0);" data-scrollTarget="gallery"><?php echo __('Képek', TD); ?></a></li>
            <li class="reviews"><a href="javascript:void(0);" data-scrollTarget="reviews"><?php echo sprintf(__('Értékelések (%d)', TD), 0); ?></a></li>
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
        <a class="gotop" href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('lap tetejére', TD); ?> <i class="fas fa-long-arrow-alt-up"></i></a>
      </div>
      <div class="programs">
        <a name="programs"></a>
        <h2><i class="fas fa-tasks"></i> <?php echo __('Programok', TD); ?></h2>
        <?php the_content(); ?>
        <a class="gotop" href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('lap tetejére', TD); ?> <i class="fas fa-long-arrow-alt-up"></i></a>
      </div>
      <div class="gallery">
        <a name="gallery"></a>
        <h2><i class="far fa-images"></i> <?php echo __('Képek', TD); ?></h2>
        <?php the_content(); ?>
        <a class="gotop" href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('lap tetejére', TD); ?> <i class="fas fa-long-arrow-alt-up"></i></a>
      </div>
      <div class="reviews">
        <a name="reviews"></a>
        <h2><i class="far fa-star"></i> <?php echo __('Értékelések', TD); ?></h2>
        <div class="no-item">
          <?php echo __('Még senki nem értékelte ezt az utazást.', TD); ?><br>
          <a href="#"><?php echo __('Értékelem az utazást', TD); ?></a>
        </div>
        <a class="gotop" href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('lap tetejére', TD); ?> <i class="fas fa-long-arrow-alt-up"></i></a>
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
      trackNavBars();
    });

    function trackNavBars() {
      var sc = $(document).scrollTop();
      var contentGroups = ['datas', 'description', 'programs', 'gallery', 'reviews'];
      var currentelem = 'datas';


      $.each(contentGroups, function(i,e){
        var topp = $('a[name='+e+']').offset().top;
        topp = topp - $('#fixnav').height() - 65 - 40;

        if( sc >= topp) {
          currentelem = e;
        }
      });

      $('#fixnav ul li:not(.'+currentelem+').active').removeClass('active');
      $('#fixnav ul li.'+currentelem).addClass('active');
    }

    function trackFixElement() {
      var navbarpos = $('.travel-header').offset().top + $('.travel-header').height()+80;
      var sc = $(document).scrollTop();
      var fixed = ( navbarpos < (sc+60) ) ? true : false;

      if (fixed) {
        $('.travel-base-wrapper').addClass('fixedelements');
      } else if( !fixed) {
        $('.travel-base-wrapper').removeClass('fixedelements');
      }

      var sidebaroffset = $('.travel-header').height() + 60 + $('.travel-nav').height() + 35 + $('.sidebar-header').height() +$('.utazas-sidebar .discount').height();
      $('.sidebar-fix-holder').css({
        top: (sidebaroffset * -1)
      });
    }

    $('*[data-scrolltarget]').click(function(){
      var target = $(this).data('scrolltarget');
      var t = $('a[name='+target+']');
      var ttop = t.offset();

      //$('#fixnav ul li.active').removeClass('active');
      //$('#fixnav ul li.'+target).addClass('active');

      $('body, html').stop().animate({
        // elem távolsága a top-tól - lebegő nav magassága - sticky header magasság - 25px padding
        scrollTop: ttop.top - $('#fixnav').height() - 65 - 25
      }, 800);
    });

  })(jQuery);
</script>
