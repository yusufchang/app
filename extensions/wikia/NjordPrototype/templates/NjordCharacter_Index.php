<?php
/**
 * @var $characterModel CharacterModuleModel
 */
?>
<div class="mom-character-module no-edit-state">
	<div class="bar">
		<div
			class="title-wrap sg-sub-title <?php if ( isset( $characterModel->title ) ) : ?>filled-state<? else : ?>zero-state<?php endif; ?>">
			<div class="edit-box">
				<div class="mc-title" contenteditable="true"><?= $characterModel->title ?></div>
				<div class="btn-bar">
					<div class="new-btn inverse-btn discard-btn sg-sub">Discard</div>
					<div class="new-btn default-btn save-btn sg-sub">Publish</div>
				</div>
			</div>
			<div class="text-wrap">
				<span class="title-text"><?= $characterModel->title ?></span>
				<span class="title-default-text">Characters</span>
				<? if ( $isAllowedToEdit ): ?>
					<img class="title-edit-btn" src="/extensions/wikia/NjordPrototype/images/pencil_b.svg">
				<? endif; ?>
			</div>
		</div>
		<? if ( $isAllowedToEdit ): ?>
			<div
				class="btn-group <?php if ( !empty( $characterModel->contentSlots ) ) : ?>filled-state<? else : ?>zero-state<?php endif; ?>">
				<div class="new-btn default-btn add-btn sg-sub">
					<img class="add-icon" src="/extensions/wikia/NjordPrototype/images/plus.svg">
					<span class="add-btn-text">Add article page</span>
				</div>
				<div class="new-btn inverse-btn settings-btn sg-sub"></div>
			</div>
		<? endif; ?>
	</div>
	<ul class="items-list <?php if ( !empty( $characterModel->contentSlots ) ) : ?>filled-state<? else : ?>zero-state<?php endif; ?>">
		<?php foreach ( $characterModel->contentSlots as $itemid => $contentSlot ): ?>
			<section class="item character" data-itemid="<?= $itemid ?>" data-title="<?= htmlspecialchars($contentSlot->title) ?>" data-link="<?= htmlspecialchars($contentSlot->link) ?>" data-image="<?= htmlspecialchars($contentSlot->image) ?>" data-description="<?= htmlspecialchars($contentSlot->description) ?>">
				<a href="#" class="remove"></a>
				<a href="<?= $contentSlot->getWikiLink() ?>" title="<?= htmlspecialchars($contentSlot->title) ?>" title="<?= htmlspecialchars($contentSlot->title) ?>">
					<img class="item-image" src="<?= $contentSlot->getImagePath() ?>"/>
					<h1 class="item-title sg-main"><?= $contentSlot->title ?></h1>
				</a>
				<!--				<h2>--><? //= $contentSlot->description ?><!--</h2>-->
			</section>
		<?php endforeach; ?>
		<div class="add-block">
			<div class="main-add-text">
				<img class="add-icon" src="/extensions/wikia/NjordPrototype/images/plus_g.svg">
				<span class="sg-main">Add an Article</span>
			</div>
			<div class="add-text sg-sub">Click on
				<span class="add-sub-text">+add article page</span>
				button on the top right corner
			</div>
		</div>
		<?php if ( empty( $characterModel->contentSlots ) ) : ?>
			<li class="item"></li>
			<li class="item"></li>
			<li class="item"></li>
			<li class="item"></li>
		<?php endif; ?>
	</ul>
	<div class="modal-wrap">
		<div class="mom-character-modal">
			<div class="modal-content">
				<div class="modal-upload">
					<div class="upload-mask"></div>
					<div class="overlay">
						<div class="overlay-flex">
							<span class="overlay-text sg-main">drop an image here</span>
						</div>
					</div>
					<div class="upload">
						<div class="upload-btn-group">
							<img class="upload-icon upload-btn" src="/extensions/wikia/NjordPrototype/images/plus.svg">
							<span class="upload-call upload-btn sg-main">add a character image</span>
							<span class="upload-call-sub sg-sub">or, drop an image here</span>
						</div>
						<div class="after-upload-btn-group">
							<img class="after-upload-icon upload-btn" src="/extensions/wikia/NjordPrototype/images/addImage.svg">
							<span class="after-upload-text sg-main upload-btn">update image</span>
						</div>
						<input name="file" type="file" hidden/>
					</div>
					<div class="image-wrap">
						<picture>
							<img class="character-image" src=""/>
						</picture>
					</div>
				</div>
				<form class="modal-form">
					<label class="label sg-sub" for="character">Full Name</label>
					<input class="input sg-main" type="text" id="character" name="charactername"/>
					<label class="label sg-sub" for="character">Article Tile</label>
					<input class="input sg-main" type="text" id="character" name="charactertitle"/>
				</form>
			</div>
			<div class="modal-bottom-bar">
				<div class="btn-group">
					<div class="new-btn inverse-btn discard-btn sg-sub">Discard</div>
					<div class="new-btn default-btn save-btn sg-sub">Publish</div>
				</div>
			</div>
		</div>
	</div>
</div>
