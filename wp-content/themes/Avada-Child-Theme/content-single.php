<div class="content-inside">
  <?php the_content(); ?>
</div>
<?php
  $author = get_the_author();
  $au_desc = nl2br(get_the_author_meta('description'));

  print_r($author_meta);
?>
<div class="author-data">
  <div class="author-title">
    <?php echo __('A cikk szerzője',TD); ?>
  </div>
  <div class="name">
    <strong><?php echo $author; ?></strong>
  </div>
  <div class="desc">
    <?php echo $au_desc; ?>
  </div>
</div>
<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
<div class="comments">
  <?php comments_template(); ?>
</div>
