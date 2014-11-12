<?php
/**
 * @var $characterModel CharacterModuleModel
 */
?>
<div class="mom-character-module no-edit-state">
	<div class="bar <?php if ( isset( $wikiData->title ) ) : ?>filled-state<? else : ?>zero-state<?php endif; ?>">
		<div class="title-wrap sg-sub-title">
			<div class="edit-box">
				<div class="mc-title" contenteditable="true"><?= $wikiData->title ?></div>
				<div class="btn-bar">
					<div class="new-btn inverse-btn discard-btn sg-sub">Discard</div>
					<div class="new-btn default-btn save-btn sg-sub">Publish</div>
				</div>
			</div>
			<span class="title-text"><?= $wikiData->title ?></span>
			<span class="title-default-text">Characters</span>
			<? if ( $isAllowedToEdit ): ?>
				<img class="title-edit-btn" src="/extensions/wikia/NjordPrototype/images/pencil_b.svg">
			<? endif; ?>
		</div>
		<div class="btn-group">
			<div class="new-btn default-btn add-btn sg-sub"><span class="add-btn-text">Add article page</span></div>
			<div class="new-btn inverse-btn settings-btn sg-sub"></div>
		</div>
	</div>
	<ul class="items-list <?php if ( isset( $characterModel->contentSlots ) ) : ?>filled-state<? else : ?>zero-state<?php endif; ?>">
		<?php foreach ( $characterModel->contentSlots as $contentSlot ): ?>
			<section class="item character">
				<a href="<?= $contentSlot->link ?>" title=" <?= $contentSlot->title ?>">
					<img class="item-image" src="<?= $contentSlot->imagePath ?>"/>
					<h1 class="item-title sg-main"><?= $contentSlot->title ?></h1>
				</a>
<!--				<h2>--><?//= $contentSlot->description ?><!--</h2>-->
			</section>
		<?php endforeach; ?>
		<div class="add-block">
			<div class="main-add-text">
				<span class="sg-main">Add an Article</span>
			</div>
			<div class="add-text sg-sub">Click on
				<span class="add-sub-text">+add article page</span>
				button on the top right corner
			</div>
		</div>
		<?php if ( !isset( $characterModel->contentSlots ) ) : ?>
			<li class="item"></li>
			<li class="item"></li>
			<li class="item"></li>
			<li class="item"></li>
		<?php endif; ?>
	</ul>
</div>
