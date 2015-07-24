<div class="curated-wrapper--top">
	<div class="wordmark-wrapper">
		<!-- taken drom EditPageLayout::EditPage template -->
			<span class="wordmark <?= $wordmark['wordmarkSize'] ?> <?= $wordmark['wordmarkType'] ?> font-<?= $wordmark['wordmarkFont'] ?>">
				<a accesskey="z" href="<?= htmlspecialchars($wordmark['mainPageURL']) ?>" title="<?= htmlspecialchars($wordmark['wordmarkText']) ?>">
					<?php if ( !empty( $wordmark['wordmarkUrl'] ) ): ?>
						<img src="<?= $wordmark['wordmarkUrl'] ?>" alt="<?= htmlspecialchars($wordmark['wordmarkText']) ?>">
					<?php elseif ( mb_substr( $wordmark['wordmarkText'], 0, 10 ) == $wordmark['wordmarkText'] ): ?>
						<?= htmlspecialchars( $wordmark['wordmarkText'] ) ?>
					<?php else: ?>
						<?= htmlspecialchars( mb_substr( $wordmark['wordmarkText'], 0, 10 ) ) . '&hellip;' ?>
					<?php endif ?>
				</a>
			</span>
	</div>
	<? if ( !empty( $wg->EnablePageShareExt ) ): ?>
		<div id="PageShareContainer" class="page-share-container">
			<?php echo F::app()->renderView( 'PageShare', 'Index' ); ?>
		</div>
	<? endif; ?>
</div>
<div class="home-top-right-ads">
	<?php
	if ( !WikiaPageType::isCorporatePage() && !$wg->EnableVideoPageToolExt && WikiaPageType::isMainPage() ) {
		echo $app->renderView( 'Ad', 'Index', [
			'slotName' => 'HOME_TOP_RIGHT_BOXAD',
			'pageTypes' => ['homepage_logged', 'corporate', 'all_ads']
		] );
	}
	?>
</div>
<div class="curated-wrapper">
	<ul class="featured-cc loading">
		<?php foreach ( $featured['items'] as $item ): ?>
			<?php if ( !empty($item['image_url']) ): ?>
				<li>
					<a href="<?= $item['article_url']; ?>" style="background-image:url(<?= $item['image_url']; ?>);">
						<img src="data:image/gif;base64,R0lGODlhEAAJAIAAAP///////yH5BAEKAAEALAAAAAAQAAkAAAIKjI+py+0Po5yUFQA7" alt="<?= $item['label']; ?>">
					</a>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<div class="curated-wrapper--bottom">
		<?php foreach( $curated as $section ): ?>
			<div class="section" style="background-image:urlx(<?= $section['image_url']; ?>);">
				<header>
					<h2><?= $section['label']; ?></h2>
				</header>
				<div class="items">
					<?php foreach( $section['items'] as $item ): ?>
						<div class="item">
							<a href="<?= $item['article_url']; ?>" style="background-image:url(<?= $item['image_url']; ?>);">
								<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"" alt="<?= $item['label']; ?>">
								<span class="label"><?= $item['label']; ?></span>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="section optional">
			<?php foreach( $optional['items'] as $item ): ?>
				<div class="item">
					<a class="curated-item" href="<?= $item['article_url']; ?>" style="background-image:url(<?= $item['image_url']; ?>);">
						<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"" alt="<?= $item['label']; ?>">
						<span class="label"><?= $item['label']; ?></span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
