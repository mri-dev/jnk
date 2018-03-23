<?php get_header(); ?>
<?php echo do_shortcode("[destination-searcher class='manual-insert']"); ?>
<?php
	$searcher = new Searcher();

	$arg = array();
	$arg['filters'] = $_GET;
	$list = $searcher->Listing( $arg );
?>
<div class="listing-utazasok">
	<div class="page-width">
		<div class="list-wrapper">
			<div class="travels">
				<?php foreach ($list as $travel): ?>
				<article class="travel">
					<div class="wrapper">
						<div class="image">
							<a href="<?=$travel->Url()?>"><img src="<?=$travel->Image()?>" alt="<?=$travel->Title()?>"></a>
						</div>
						<div class="title">
							<h3><a href="<?=$travel->Url()?>"><?=$travel->Title()?></a></h3>
						</div>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();
