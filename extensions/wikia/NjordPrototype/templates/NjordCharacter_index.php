<?php
/**
 * @var $characterModel CharacterModuleModel
 */
?>
<div class="mom-character-module">
	<?php foreach ( $characterModel->contentSlots as $contentSlot ): ?>
	<section class="mom-character">
		<a href="<?= $contentSlot->link ?>" title=" <?= $contentSlot->title ?>">
			<img src="<?= $contentSlot->image ?>"/>
			<h1><?= $contentSlot->title ?></h1>
			<h2><?= $contentSlot->description ?></h2>
		</a>
	</section>
	<?php endforeach; ?>

</div>
