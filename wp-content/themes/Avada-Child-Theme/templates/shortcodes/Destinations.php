<?php
  $dest = get_terms(array(
    'taxonomy' => 'utazas_uticel',
    'number' => 9,
    'hide_empty' => true,
    'orderby' => 'name'
  ));
?>
<h4 class="widget-title"><?=__('Úti célok', TD)?></h4>
<div class="wrapper">
<?php foreach ( (array)$dest as $d ): ?>
  <?php
    if (function_exists('z_taxonomy_image_url')){
      $image = z_taxonomy_image_url($d->term_id);
      $image = ( !$image ) ? IMG . '/no-travel-img.jpg' : $image;
    } else {
      $image = IMG . '/no-travel-img.jpg';
    }
  ?>
  <div class="destination">
    <div class="w">
      <div class="title">
        <?=$d->name?><br>
        <span class="ct">- <?=sprintf(__('%d ajánlat', TD), $d->count)?> -</span>
      </div>
      <a href="/utazas/?cities=<?=$d->name?>"><img src="<?=$image?>" alt="<?=$d->name?>"></a>
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
