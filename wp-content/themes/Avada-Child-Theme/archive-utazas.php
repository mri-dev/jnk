<?php get_header(); ?>
<?php echo do_shortcode("[destination-searcher class='manual-insert']"); ?>
<?php
	$searcher = new Searcher();
	$template = new ShortcodeTemplates('SearcherSc/standard');

	$arg = array();
	$arg['filters'] = $_GET;
	$list = $searcher->Listing( $arg );
?>
<div class="listing-utazasok">
	<div class="page-width">
		<div class="list-wrapper">
      <?php if (empty($list)): ?>
        <div class="no-item">
          <i class="fa fa-plane"></i>
          <h3><?php echo __('Nincs megjeleníthető utazás.',TD); ?></h3>
          <?php echo __('Keresési feltételei alapján nem találtunk Önnek megfelelő utazást.',TD); ?>
        </div>
      <?php else: ?>
        <div class="travels">
				<?php foreach ($list as $travel):
					echo $template->load_template(array('travel' => $travel));
				 endforeach; ?>
         </div>
         <?php echo (new ShortcodeTemplates('SearcherSc/js'))->load_template(); ?>
      <?php endif; ?>
		</div>
	</div>
</div>
<?php get_footer();
