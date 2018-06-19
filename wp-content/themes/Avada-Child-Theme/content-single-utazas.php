<?php global $travel; ?>
<?php
  $durations = $travel->showDuration();
  $ellatasok = $travel->showEllatas();
  $utazas_mod = $travel->showUtazasMod();
  $travel_from = $travel->getTravelFrom();
  $travel_to = $travel->getTravelTo();
  $gallery_id = $travel->getGalleryID();
  $programs = $travel->getPrograms();
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
            <?php if ($travel_from): ?>
            <div class="travel-from">
              <i class="fas fa-sign-out-alt" data-fa-transform="rotate--45"></i>
              <?php echo $travel_from; ?>
            </div>
            <?php endif; ?>
            <?php if ($travel_to): ?>
            <div class="travel-to">
              <i class="fas fa-sign-in-alt" data-fa-transform="rotate-45"></i>
              <?php echo $travel_to; ?>
            </div>
            <?php endif; ?>
            <div class="ellatas">
              <i class="fas fa-utensils"></i>
              <?php echo implode(', ', $ellatasok); ?>
            </div>
            <div class="travel-by">
              <i class="fas fa-car"></i>
              <?php echo implode(', ', $utazas_mod); ?>
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
            <?php if ($gallery_id): ?>
            <li class="gallery"><a href="javascript:void(0);" data-scrollTarget="gallery"><?php echo __('Képek', TD); ?></a></li>
            <?php endif; ?>
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
      <?php if ($programs): ?>
      <div class="programs">
        <a name="programs"></a>
        <h2><i class="fas fa-tasks"></i> <?php echo __('Programok', TD); ?></h2>
        <div class="program-list">
          <?php foreach ($programs as $program): ?>
          <?php
            $img = get_the_post_thumbnail_url($program->ID);
          ?>
          <div class="program">
            <div class="wrapper">
              <div class="image">
                <img src="<?=$img?>" alt="<?=$program->post_title?>">
              </div>
              <div class="data">
                <div class="title">
                  <h3><a href="<?=get_permalink($program->ID)?>" target="_blank"><?=$program->post_title?></a></h3>
                </div>
                <div class="sdesc">
                  <?php echo get_the_excerpt($program->ID); ?>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a class="gotop" href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('lap tetejére', TD); ?> <i class="fas fa-long-arrow-alt-up"></i></a>
      </div>
      <?php endif; ?>
      <?php if ($gallery_id): ?>
      <div class="gallery">
        <a name="gallery"></a>
        <h2><i class="far fa-images"></i> <?php echo __('Képek', TD); ?></h2>
        <?php photo_gallery($gallery_id); ?>
        <a class="gotop" href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('lap tetejére', TD); ?> <i class="fas fa-long-arrow-alt-up"></i></a>
      </div>
      <?php endif; ?>
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
  <div class="gettraveloffer">
    <a name="getoffer"></a>
    <div class="header">
      <h2><?=__('Ajánlatkérés', TD)?></h2>
    </div>
    <div class="wrapper">
      <div class="page-width">
        <?php echo get_template_part('templates/travelcalc'); ?>
      </div>
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
        if (typeof $('a[name='+e+']').offset() !== 'undefined') {
          var topp = $('a[name='+e+']').offset().top;
          topp = topp - $('#fixnav').height() - 65 - 40 - $('#wpadminbar').height();

          if( sc >= topp) {
            currentelem = e;
          }
        }
      });

      $('#fixnav ul li:not(.'+currentelem+').active').removeClass('active');
      $('#fixnav ul li.'+currentelem).addClass('active');
    }

    function trackFixElement() {

      var navbarpos = $('.travel-header').offset().top + $('.travel-header').height()+60-$('#wpadminbar').height();
      var sc = $(document).scrollTop();
      var fixed = ( navbarpos < (sc+60) ) ? true : false;

      if (fixed) {
        $('.travel-base-wrapper, .sidebar-fix-holder').addClass('fixedelements');
      } else if( !fixed) {
        $('.travel-base-wrapper, .sidebar-fix-holder').removeClass('fixedelements');
      }

      $('.sidebar-header').animate({
        height: $('.travel-header').height() + 60
      }, function(){
        var sidebaroffset = 35 + $('#fixnav').height() + $('.travel-header').height() + 60 + $('.sidebar-fix-holder .discount').height();
        $('.sidebar-fix-holder').css({
          top: (sidebaroffset * -1)
        });
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
        scrollTop: ttop.top - $('#fixnav').height() - 65 - 25 - $('#wpadminbar').height()
      }, 800);
    });

  })(jQuery);
</script>
