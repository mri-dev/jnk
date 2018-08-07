<?php global $travel; ?>
<?php
  $durations = $travel->showDuration();
  $ellatasok = $travel->showEllatas();
  $utazas_mod = $travel->showUtazasMod();
  $travel_from = $travel->getTravelFrom();
  $travel_to = $travel->getTravelTo();
  $gallery_id = $travel->getGalleryID();
  $programs = $travel->getPrograms();
  $testimonials = $travel->getTestimonials();
  $ar_comment = $travel->getPriceComment();
  $egyeni_utazas = $travel->isEgyeni();
  $stars = $travel->getHotelStars();
  $hotel_type = $travel->getHotelType();
  $max_befogadas = $travel->maxBefogadas();

  $travel->logView();

  if ($egyeni_utazas) {
    $what = __('szállást', TD);
  } else {
    $what = __('utazást', TD);
  }
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
            <?php if ($egyeni_utazas): ?>
              <div class="hotel-type" title="<?=__('Hotel besorolása',TD)?>">
                <i class="fas fa-star"></i>
                <?=$hotel_type->name?>
              </div>
            <?php else: ?>
              <div class="people-restricts" title="<?=__('Elérhető létszám adatok: min - max',TD)?>">
                <i class="fas fa-users"></i>
                <?php if ($max_befogadas): ?>
                  <?php echo $max_befogadas; ?>
                <?php else: ?>
                  <?=__('Létszámtól független',TD)?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
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
            <?php if ($programs): ?>
            <li class="programs"><a href="javascript:void(0);" data-scrollTarget="programs"><?php echo __('Programok', TD); ?></a></li>
            <?php endif; ?>
            <?php if ($gallery_id): ?>
            <li class="gallery"><a href="javascript:void(0);" data-scrollTarget="gallery"><?php echo __('Képek', TD); ?></a></li>
            <?php endif; ?>
            <li class="reviews"><a href="javascript:void(0);" data-scrollTarget="reviews"><?php echo sprintf(__('Értékelések (%d)', TD), count($testimonials)); ?></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="base-content-holder">
    <div class="travel-contents">
      <div class="description">
        <a name="description"></a>
        <?php if ($egyeni_utazas): ?>
          <h2><i class="far fa-building"></i> <?php echo __('Hotel leírása', TD); ?></h2>
        <?php else: ?>
          <h2><i class="far fa-file-alt"></i> <?php echo __('Utazás ismertetése', TD); ?></h2>
        <?php endif; ?>

        <?php the_content(); ?>
        <?php if (!empty($ar_comment)): ?>
        <div class="price-comment">
          <div class="ico">
            <i class="fas fa-asterisk"></i>
          </div>
          <div class="text">
            <?php echo $ar_comment; ?>
          </div>
        </div>
        <?php endif; ?>
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
        <?php echo do_shortcode('[Best_Wordpress_Gallery use_option_defaults="1" type="gallery" theme_id="1" gallery_id="'.$gallery_id.'" tag="0" gallery_type="thumbnails"]'); ?>
        <a class="gotop" href="javascript:void(0);" data-scrollTarget="datas"><?php echo __('lap tetejére', TD); ?> <i class="fas fa-long-arrow-alt-up"></i></a>
      </div>
      <?php endif; ?>
      <div class="reviews" ng-controller="TestimonialMaker">
        <a name="reviews"></a>
        <h2><i class="far fa-star"></i> <?php echo __('Értékelések', TD); ?> (<?=count($testimonials)?>)</h2>
        <?php if ( count($testimonials) == 0 ): ?>
          <div class="no-item">
            <?php echo __('Még senki nem írt véleményt.', TD); ?><br>
            <a href="javascript:void(0);" ng-click="tglCreator()"><?php echo ucfirst($what).' '.__('értékelése', TD); ?></a>
          </div>
        <?php else: ?>
          <div class="testimonial-content">
            <div class="wrapper">
              <?php $ti = 0; ?>
              <div class="first5">
              <?php foreach ($testimonials as $test): $ti++; ?>
              <div class="comment">
                <div class="wrapper">
                  <div class="desc">
                    <?php echo $test->post_content; ?>
                  </div>
                  <div class="author">
                    <div class="name">
                      <i class="far fa-user-circle"></i> <?php echo $test->client_name; ?>
                    </div>
                    <div class="destination">
                      <i class="fas fa-map-marker-alt"></i> <?php echo $test->destination; ?>
                    </div>
                    <div class="posteddate">
                      <?php echo get_the_date('Y. m. d.',$test); ?>
                    </div>
                  </div>
                </div>
              </div>
              <?php if ($ti == 5): ?>
              </div>
              <div class="load-more-comment">
                <a href="javascript:void(0);"><?php echo sprintf(__('További %d vélemény olvasása'), (count($testimonials)-5)); ?> >></a>
              </div>
              <div class="more-comment">
              <?php endif; ?>
              <?php endforeach; ?>
              </div>
              <a href="javascript:void(0);" class="adder" ng-click="tglCreator()"><i class="fas fa-pencil-alt"></i> <?php echo __('Új értékelés beküldése', TD); ?></a>
            </div>
          </div>
        <?php endif; ?>

        <?php //print_r($testimonials); ?>
        <div class="testimonial-creator" ng-show="creatorshowed" ng-init="init(<?=$post->ID?>)">
          <div class="wrapper">
            <div class="header">
              <h3><?php echo __('Új értékelés beküldése', TD); ?></h3>
            </div>
            <div class="cont">
              <div class="name">
                <div class="w">
                  <label for="testi_name"><?php echo __('Az Ön neve', TD); ?> *</label>
                  <input type="text" id="testi_name" ng-model="content.client_name">
                </div>
              </div>
              <div class="city">
                <div class="w">
                  <label for="testi_destination"><?php echo __('Város / Úti cél', TD); ?> *</label>
                  <input type="text" id="testi_destination" ng-model="content.destination">
                </div>
              </div>
              <div class="msg">
                <div class="w">
                  <label for="testi_msg"><?php echo __('Vélemény', TD); ?> *</label>
                  <textarea ng-model="content.msg" maxlength="250" placeholder="<?php echo __('Kérjük, itt fejtse ki véleményét...', TD); ?>"></textarea>
                  <div class="avb" ng-class="(content.msg.length >= 240)?'nomore':''">
                    250 / <strong>{{(250-content.msg.length)| number:0}}</strong>
                  </div>
                </div>
              </div>
              <div class="send">
                <div class="w">
                  <div class="input-ast" ng-hide="(content.client_name && content.destination && content.msg)">
                    <?php echo __('Az értékelés beküldéséhez töltse ki a form csillagozott (*) mezőit.', TD); ?>
                  </div>
                  <div class="sending" ng-show="sending">
                      <?php echo __('Értékelés beküldése folyamatban...', TD); ?>
                  </div>
                  <div class="sended" ng-show="sended">
                    <?php echo __('Köszönjük visszajelzését. Értékelését sikeresen befogadtuk, mely munkatársaink jóváhagyása után jelenhet meg az adatlapon.', TD); ?>
                  </div>
                  <div class="" ng-hide="sending">
                    <button ng-click="SendTestimonial()" ng-show="(content.client_name && content.destination && content.msg)"><?php echo __('Értékelés beküldése', TD); ?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
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

    $('.load-more-comment > a').click(function(){
      $('.more-comment').addClass('show');
      $(this).hide(0);
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
