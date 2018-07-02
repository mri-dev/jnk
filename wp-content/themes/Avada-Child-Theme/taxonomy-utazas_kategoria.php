<?php get_header(); ?>
<?php //echo do_shortcode("[destination-searcher class='manual-insert']"); ?>
<?php
	$tax = get_queried_object();
	$terms = get_terms( array(
		'taxonomy' => 'utazas_kategoria',
		'parent' => $tax->term_id
	));

	$template = new ShortcodeTemplates('SearcherSc/standard');
	$is_tematic = ($tax && $tax->slug == 'tematikus') ? true : false;

	/* * /
	echo '<pre>';
	print_r($tax);
	echo '</pre>';
	/* */
?>
<div class="listing-utazasok">
	<div class="page-width">
		<div class="list-wrapper">
			<?php if ($is_tematic): ?>
				<?php foreach ( $terms as $t ): ?>
				<div class="list-group">
				<?php
					$arg = array();
					$arg['filters'] = $_GET;
					$arg['tax_id'] = $t->term_id;
					$arg['limit'] = 3;
					$arg['orderby'] = 'rand';

					$searcher = new Searcher();
					$list = $searcher->Listing( $arg );

					if( !empty($list) ):
				?>
				<div class="group-title">
					<h2><?=$t->name?></h2>
					<?php if (!empty($t->description)): ?>
					<div class="desc">
						<?php echo $t->description; ?>
					</div>
					<?php endif; ?>
				</div>
				<div class="travels">
				<?php foreach ($list as $travel):
					echo $template->load_template(array('travel' => $travel));
				 endforeach; ?>
         </div>
				 <div class="more-travels">
				 		<a href="<?=get_term_link($t)?>"><?=sprintf(__('Összes <strong>%s</strong> utazás',TD), $t->name)?> >></a>
				 </div>
			 <?php endif; ?>
			 </div>
		 	 <?php endforeach; ?>
			<?php else: ?>
				<?php
					$arg = array();
					$arg['filters'] = $_GET;
					$arg['tax_id'] = $tax->term_id;
					$arg['limit'] = 30;
					$searcher = new Searcher();
					$list = $searcher->Listing( $arg );
				?>
				<?php if (empty($list)): ?>
					<div class="no-item">
						<i class="fa fa-plane"></i>
						<h3><?php echo __('Nincs találat', TD); ?></h3>
						<?php echo __('A keresési és szűrési feltételek alapján ebben a kategóriában nem találtunk Önnek utazási ajánlatot.', TD); ?><br>
						<a href="/utazas-kategoria/tematikus"><?php echo __('Böngészés a tematikus kategóriák között',TD); ?> >></a>
					</div>
				<?php else: ?>
					<div class="travels">
					<?php foreach ($list as $travel):
						echo $template->load_template(array('travel' => $travel));
					 endforeach; ?>
	         </div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php echo (new ShortcodeTemplates('SearcherSc/js'))->load_template(); ?>
		</div>
	</div>
</div>
<?php get_footer();
