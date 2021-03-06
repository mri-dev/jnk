<h4 class="widget-title"><?=__('Népszerű úti célok', 'jnk')?></h4>
<div class="wrapper">
<?php foreach ( (array)$dest as $d ): ?>
  <?php
    if (function_exists('z_taxonomy_image_url')){
      $image = z_taxonomy_image_url($d->term_id);
      $image = ( !$image ) ? IMG . '/no-travel-img.jpg' : $image;
    } else {
      $image = IMG . '/no-travel-img.jpg';
    }
    $url = site_url();
    $type = 'csoportos';

    if (get_locale() != 'hu_HU') {
      $type = 'group';
    }
  ?>
  <div class="destination">
    <div class="w">
      <div class="title">
        <?=$d->name?><br>
        <span class="ct">- <?=sprintf(__('%d ajánlat', 'jnk'), $d->count)?> -</span>
      </div>
      <a href="<?=$url?>/utazas/?type=<?=$type?>&cities=<?=$d->name?>"><img src="<?=$image?>" alt="<?=$d->name?>"></a>
    </div>

  </div>
<?php endforeach; ?>
<script type="text/javascript">
  (function($){
    $('.destination > .w').css({
      height: $('.destination > .w').width()
    });
  })(jQuery);
</script>
</div>
