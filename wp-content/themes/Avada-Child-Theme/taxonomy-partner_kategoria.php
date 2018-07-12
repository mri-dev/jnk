<?php get_header(); ?>
<?php
	$template = new ShortcodeTemplates('Partnerek/standard');
?>
<div class="listing-partners">
	<div class="page-width">
		<div class="list-wrapper">
      <?php
        $list = get_posts(array(
          'post_type' => 'partnerek',
          'posts_per_page' => -1
        ));
      ?>
      <?php if (empty($list)): ?>
        <div class="no-item">
          <i class="fa fa-briefcase"></i>
          <h3><?php echo __('Nincs megjelenítendő partner.', TD); ?></h3>
          <?php echo __('Kérjük, nézzen vissza később!', TD); ?>
        </div>
      <?php else: ?>
        <div class="partners">
        <?php foreach ($list as $travel):
          echo $template->load_template(array('partner' => $travel));
         endforeach; ?>
         </div>
      <?php endif; ?>
		</div>
		<?php echo (new ShortcodeTemplates('Partnerek/js'))->load_template(); ?>
		</div>
	</div>
</div>
<?php get_footer();
