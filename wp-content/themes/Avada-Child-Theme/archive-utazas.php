<?php get_header(); ?>
<?php echo do_shortcode("[destination-searcher class='manual-insert']"); ?>
<?php
	$searcher = new Searcher();
	$template = new ShortcodeTemplates('SearcherSc/standard');

if (isset($_GET['type']) && $_GET['type'] != '') {
	$arg = array();
	$arg['filters'] = $_GET;
	$arg['limit'] = 12;
	$arg['page'] = (isset($_GET['page'])) ? $_GET['page'] : 1;
	$list = $searcher->Listing( $arg );
	$pages = $searcher->pages;
	$filters = $searcher->acceptedFilters();
} else {
	$group_listing = true;
}

?>
<div class="listing-utazasok">
	<div class="page-width">
		<?php if ($group_listing): ?>
			<div class="list-wrapper">
				<?php	$t = get_term_by('slug', 'egyeni', 'utazas_kategoria'); ?>
				<div class="list-group">
					<div class="group-title">
						<h2><a href="/utazas-kategoria/egyeni/"><?=$t->name?></a></h2>
						<?php if ($t->description != ''): ?>
						<div class="desc">
							<?=$t->description?>
						</div>
						<?php endif; ?>
					</div>
					<?php echo do_shortcode('[searcher limit="3" utazas_kategoria="egyeni" orderby="rand"]'); ?>
					<div class="more-travels">
				 		<a href="/utazas-kategoria/egyeni/"><?=sprintf(__('További <strong>%s</strong>','jnk'), $t->name)?> >></a>
				 	</div>
				</div>
				<?php	$t = get_term_by('slug', 'csoportos', 'utazas_kategoria'); ?>
				<div class="list-group">
					<div class="group-title">
						<h2><a href="/utazas-kategoria/csoportos/"><?=$t->name?></a></h2>
						<?php if ($t->description != ''): ?>
						<div class="desc">
							<?=$t->description?>
						</div>
						<?php endif; ?>
					</div>
					<?php echo do_shortcode('[searcher limit="3" utazas_kategoria="csoportos" orderby="rand"]'); ?>
					<div class="more-travels">
				 		<a href="/utazas-kategoria/csoportos/"><?=sprintf(__('További <strong>%s</strong>','jnk'), $t->name)?> >></a>
				 	</div>
				</div>
			</div>
		<?php else: ?>
			<h2><?=__('Keresési eredmény', 'jnk')?>:</h2>
			<div class="pages">
				<?=sprintf(__('%d db találat &mdash; %d / <strong>%d. oldal</strong>', 'jnk'), $pages['items'], $pages['max'], $pages['current'])?>
			</div>
			<?php if (!empty($filters)): ?>
			<div class="filters">
				<div class="">
					<i class="fa fa-filter"></i>
				</div>
				<?php if ( isset($filters['tag']) ): ?>
					<div class="tag">
						<?=__('Címke', 'jnk')?>: <span class="opt"><?=$filters['tag']['label']?></span>
					</div>
				<?php endif; ?>
				<?php if ( isset($filters['type']) ): ?>
					<div class="type">
						<?=__('Típus', 'jnk')?>: <span class="opt"><?=$filters['type']['label']?></span>
					</div>
				<?php endif; ?>
				<?php if ( isset($filters['el']) && count($filters['el']) != 0 ): ?>
					<div class="el">
						<?=__('Ellátás', 'jnk')?>: <? foreach( $filters['el'] as $v ): ?><span class="opt"><?=$v['label']?></span><? endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( isset($filters['dur']) && count($filters['dur']) != 0 ): ?>
					<div class="dur">
						<?=__('Utazás hossza', 'jnk')?>: <? foreach( $filters['dur'] as $v ): ?><span class="opt"><?=$v['label']?></span><? endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( isset($filters['um']) && count($filters['um']) != 0 ): ?>
					<div class="um">
						<?=__('Utazás módja', 'jnk')?>: <? foreach( $filters['um'] as $v ): ?><span class="opt"><?=$v['label']?></span><? endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( isset($filters['szolg']) && count($filters['szolg']) != 0 ): ?>
					<div class="szolg">
						<?=__('Szolgáltatás', 'jnk')?>: <? foreach( $filters['szolg'] as $v ): ?><span class="opt"><?=$v['label']?></span><? endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<div class="list-wrapper">
				<?php if (empty($list)): ?>
					<div class="no-item">
						<i class="fa fa-plane"></i>
						<h3><?php echo __('Nincs megjeleníthető utazás.','jnk'); ?></h3>
						<?php echo __('Keresési feltételei alapján nem találtunk Önnek megfelelő utazást.','jnk'); ?>
					</div>
				<?php else: ?>
					<div class="travels">
					<?php foreach ($list as $travel):
						echo $template->load_template(array('travel' => $travel));
					 endforeach; ?>
					 </div>
					 <?php if ($pages && $pages['max'] > 1): ?>
						<?php echo $searcher->pagination(); ?>
					 <?php endif; ?>
					 <?php echo (new ShortcodeTemplates('SearcherSc/js'))->load_template(); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<?php get_footer();
