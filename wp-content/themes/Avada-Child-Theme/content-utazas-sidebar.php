<?php
global $travel;

$destionations = $travel->showDestinations();
$durations = $travel->showDuration();
$discount = $travel->getDiscount();
$kiemelt = $travel->isKiemelt();
$price_comment = $travel->getPriceComment();
$tags = $travel->getTags();
$ajutazasok = $travel->getRecommendedTravelIDS();
$kapcs_utazas = $travel->getRecommendedTravelIDS( $tags );
$posts_ids = $travel->getRecommendedPostsIDSByTags( $tags );

// Ajánlott utazások kereső
$rec_utazas = new Searcher();
$arg = array();
$arg['ids'] = $ajutazasok;
$arg['limit'] = -1;
$arg['orderby'] = 'rand';
$ajutazasok = $rec_utazas->Listing( $arg );

// Kapcsolódó utazások kereső
$rec_utazas = new Searcher();
$arg = array();
$arg['ids'] = $kapcs_utazas;
$arg['limit'] = 3;
$arg['orderby'] = 'post__in';
$kapcs_utazas = $rec_utazas->Listing( $arg );
?>
<div class="sidebar-fix-holder">
  <div class="utazas-sidebar">
    <?php if ($discount): ?>
    <div class="discount">
      <?php echo $discount['percent'].__('% leárazás',TD); ?>
    </div>
    <?php endif; ?>
    <div class="sidebar-header">
      <div class="swrapper">
        <div class="price">
          <?php if ( (int)$travel->getPrice() !== 0): ?>
            <div class="c">
              <?php echo __('Legkedvezőbb alapár', TD); ?>:
            </div>
            <?php if ($discount): ?>
            <span class="old">
              <?=$travel->getPriceBefore()?><?=number_format($travel->getOriginalPrice(), 0, '', ' ')?><?=$travel->getPriceAfter()?>
            </span>
            <?php endif; ?>
            <span class="current"><?=$travel->getPriceBefore()?><?=number_format($travel->getPrice(), 0, '', ' ')?><?=$travel->getPriceAfter()?><?=($price_comment)?'<sup>*</sup>':''?></span>
          <?php else: ?>
            <div class="c">
              <?php echo __('Utazás ára', TD); ?>:
            </div>
            <span class="current">Egyedi árazás</span>
            <div class="f">
              <?php echo __('Kérje egyedi ajánlatunkat!', TD); ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="getoffer">
      <a href="javascript:void(0);" data-scrollTarget="getoffer"><?php echo __('Ajánlatot kérek', TD); ?> <i class="far fa-arrow-alt-circle-right"></i></a>
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
                  <?=$u->getPriceBefore()?><?=number_format($u->getOriginalPrice(), 0, '', ' ')?><?=$u->getPriceAfter()?>
                </div>
                <?php endif; ?>
                <div class="current">
                  <?=$u->getPriceBefore()?><?=number_format($u->getPrice(), 0, '', ' ')?><?=$u->getPriceAfter()?>
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

  <?php if ($ajutazasok): ?>
  <div class="sidebar-group">
    <h3><?php echo __('Érdekes lehet még', TD); ?></h3>
    <div class="recommended-travels">
      <?php
      foreach ($ajutazasok as $u):
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
                  <?=$u->getPriceBefore()?><?=number_format($u->getOriginalPrice(), 0, '', ' ')?><?=$u->getPriceAfter()?>
                </div>
                <?php endif; ?>
                <div class="current">
                  <?=$u->getPriceBefore()?><?=number_format($u->getPrice(), 0, '', ' ')?><?=$u->getPriceAfter()?>
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

  <?php if ($posts_ids): ?>
  <?php
    $popularpost = new WP_Query(array(
      'posts_per_page' => 3,
      'post__in' => $posts_ids,
      'orderby' => 'post__in'
    ));
  ?>
  <div class="sidebar-group recent-post">
    <h3><?php echo __('Kapcsolódó cikkek', TD); ?></h3>
    <div class="popular-articles">
      <?php while ( $popularpost->have_posts() ) : $popularpost->the_post(); $cats = wp_get_post_categories($post->ID, array('fields' => 'all_with_object_id')); ?>
        <article class="imaged">
          <div class="img">
            <a href="<?php echo the_permalink(); ?>"><img src="<?php echo get_the_post_thumbnail_url($post->ID); ?>" alt="<?php echo the_title(); ?>"></a>
          </div>
          <div class="datas">
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
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($tags): ?>
  <div class="sidebar-group tag-sidebar">
    <h3><?php echo __('Címkék', TD); ?></h3>
    <div class="tags">
    <?php foreach ($tags as $tag): ?>
      <div class="tag">
        <a href="/utazas/?search=&tag=<?=$tag->slug?>"><?php echo $tag->name; ?></a>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
