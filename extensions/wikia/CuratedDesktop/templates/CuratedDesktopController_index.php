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
</div>
<div class="curated-wrapper--bottom">
	<?php foreach( $curated as $id => $section ): ?>
		<div class="curated-item-wrapper section" data-section="0" data-activate="<?= $id + 1; ?>">
			<div class="curated-item" style="background-image:url(<?= $section['image_url']; ?>);">
				<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"" alt="<?= $section['label']; ?>">
				<span class="label"><?= $section['label']; ?></span>
			</div>
		</div>
		<?php foreach( $section['items'] as $item ): ?>
			<div class="curated-item-wrapper item" data-section="<?= $id + 1; ?>">
				<a class="curated-item" href="<?= $item['article_url']; ?>" style="background-image:url(<?= $item['image_url']; ?>);">
					<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"" alt="<?= $item['label']; ?>">
					<span class="label"><?= $item['label']; ?></span>
				</a>
			</div>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<?php foreach( $optional['items'] as $item ): ?>
		<div class="curated-item-wrapper item" data-section="0">
			<a class="curated-item" href="<?= $item['article_url']; ?>" style="background-image:url(<?= $item['image_url']; ?>);">
				<img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"" alt="<?= $item['label']; ?>">
				<span class="label"><?= $item['label']; ?></span>
			</a>
		</div>
	<?php endforeach; ?>
</div>
<pre style="display: none">
	<?php s($featured); ?>
	<?php s($curated); ?>
	<?php s($optional); ?>
</pre>
