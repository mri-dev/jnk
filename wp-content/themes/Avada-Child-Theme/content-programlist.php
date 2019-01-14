<article class="program">
  <div class="wrapper">
    <div class="image">
      <?php $image = get_the_post_thumbnail_url(get_the_ID()); ?>
      <a title="<? echo the_title(); ?>" href="<? echo the_permalink(); ?>"><img src="<?=($image) ? $image : IMG.'/no-travel-img.jpg'?>" alt="<? echo the_title(); ?>"></a>
    </div>
    <div class="datas">
      <div class="title">
        <h3><a title="<? echo the_title(); ?>" href="<? echo the_permalink(); ?>"><? echo the_title(); ?></a></h3>
      </div>
      <div class="desc">
        <?php echo the_excerpt(); ?>
      </div>
    </div>
  </div>
</article>
