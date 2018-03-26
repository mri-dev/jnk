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
				<?php foreach ($list as $travel):
					$destionations = $travel->showDestinations();
					$durations = $travel->showDuration();
					$discount = $travel->getDiscount();
					$kiemelt = $travel->isKiemelt();
				?>
				<article class="travel">
					<div class="wrapper">
						<div class="badges">
						<?php if ($kiemelt): ?>
							<div class="highlighted">
								<?php echo __('Kiemelt',TD); ?>
							</div>
						<?php endif; ?>
						<?php if ($discount): ?>
							<div class="discounted">
								<?php echo $discount['percent'].__('% leárazás',TD); ?>
							</div>
						<?php endif; ?>
						</div>
						<div class="image">
							<a href="<?=$travel->Url()?>"><img src="<?=$travel->Image()?>" alt="<?=$travel->Title()?>"></a>
						</div>
						<div class="datas">
							<div class="title">
								<h3><a href="<?=$travel->Url()?>"><?=$travel->Title()?></a></h3>
							</div>
							<div class="price">
								<div class="old">
									112 000 Ft
								</div>
								<div class="current">
									56 000 Ft
								</div>
							</div>
							<?php
								$destionations = $travel->showDestinations();
							?>
							<div class="position">
								<i class="fa fa-map-pin"></i>
								<?php if (count($destionations) > 5): ?>
									<span title="<?php echo implode(', ', $destionations); ?>"><?php echo sprintf(__('%d úti célt érint', TD), count($destionations)); ?></span>
								<?php else: ?>
									<?php echo implode(', ', $destionations); ?>
								<?php endif; ?>
							</div>
							<div class="duration">
								<i class="far fa-clock"></i> <?php if (count($durations) > 5): ?>
									<span title="<?php echo implode(', ', $durations); ?>"><?php echo sprintf(__('%d utazási hossz elérhető', TD), count($durations)); ?></span>
								<?php else: ?>
									<?php echo implode(', ', $durations); ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();
