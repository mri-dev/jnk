<?php get_header(); ?>
<?php echo do_shortcode("[destination-searcher class='manual-insert']"); ?>
<?php
	$tax = get_queried_object();
	$terms = get_terms( array(
		'taxonomy' => 'utazas_kategoria',
		'parent' => $tax->term_id
	));

	$template = new ShortcodeTemplates('SearcherSc/standard');
	$is_tematic = ($tax && $tax->slug == 'tematikus') ? true : false;

	$grouped = (in_array($tax->slug, array('egyeni', 'csoportos'))) ? true : false;


	//
	if ($grouped)
	{
		$all_ids = array();
		$all = get_posts(array(
			'post_type' => 'utazas',
			'posts_per_page' => -1,
			'orderby' => 'name',
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'utazas_kategoria',
					'terms' => array($tax->slug),
					'field' => 'slug',
					'operator' => 'IN'
				)
			)
		));
		foreach ((array)$all as $a ) {
			$all_ids[] = $a->ID;
		}
		unset($all);

		$places = wp_get_object_terms($all_ids, 'utazas_uticel');

		/*
		// count alapján DESC order
		usort($places, function ($item1, $item2) {
      if ($item1->count == $item2->count) return 0;
      return $item1->count < $item2->count? 1 : -1;
    });*/
	}

	/* * /
	echo '<pre>';
	print_r($all_ids);
	echo '</pre>';
	/* */
?>
<div class="listing-utazasok">
	<div class="page-width">
		<div class="list-wrapper">
		<?php if ($grouped): ?>
			<div class="list-group">
				<div class="group-title">
					<h2><?=__('Kiemelt', TD)?> <?=$tax->name?></h2>
				</div>
				<?php echo do_shortcode('[searcher limit="3" kiemelt="1" utazas_kategoria="'.$tax->slug.'" orderby="rand"]'); ?>
				<div class="more-travels">
					<a href="/utazas/?type=<?=$tax->slug?>&kiemelt=1"><?=sprintf(__('Összes kiemelt <strong>%s</strong>',TD), $tax->name)?> >></a>
				</div>
			</div>
			<?php if ($places): ?>
				<br><br>
				<?php foreach ((array)$places as $place): if($place->parent != 0) continue; ?>
					<div class="list-group">
						<div class="group-title">
							<h2><?=$place->name?></h2>
						</div>
						<?php echo do_shortcode('[searcher limit="3" utazas_kategoria="'.$tax->slug.'" cities="'.$place->name.'" orderby="rand"]'); ?>
						<div class="more-travels">
							<a href="/utazas/?type=<?=$tax->slug?>&cities=<?=$place->name?>"><?=sprintf(__('Összes utazás ide: <strong>%s</strong>',TD), $place->name)?> >></a>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php else: ?>
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
		<?php endif; ?>
		</div>
		<?php echo (new ShortcodeTemplates('SearcherSc/js'))->load_template(); ?>
		</div>
	</div>
</div>
<?php get_footer();
