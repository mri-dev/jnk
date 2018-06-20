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
			<div class="travels">
				<?php foreach ($list as $travel):
					echo $template->load_template(array('travel' => $travel));
				 endforeach; ?>
				 <?php echo (new ShortcodeTemplates('SearcherSc/js'))->load_template(); ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();
