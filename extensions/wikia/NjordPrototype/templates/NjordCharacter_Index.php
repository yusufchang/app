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
					<span class="add-btn-text">Add a character</span>
				</div>
<!--				<div class="new-btn inverse-btn settings-btn sg-sub"></div>-->
			</div>
		<? endif; ?>
	</div>
	<ul class="items-list <?php if ( !empty( $characterModel->contentSlots ) ) : ?>filled-state<? else : ?>zero-state<?php endif; ?>">
		<?php foreach ( $characterModel->contentSlots as $itemid => $contentSlot ): ?>
			<section class="item character" data-itemid="<?= $itemid ?>" data-title="<?= htmlspecialchars($contentSlot->title) ?>" data-link="<?= htmlspecialchars($contentSlot->link) ?>" data-image="<?= htmlspecialchars($contentSlot->image) ?>" data-cropposition="<?= htmlspecialchars($contentSlot->cropposition) ?>" data-description="<?= htmlspecialchars($contentSlot->description) ?>" data-actor="<?= htmlspecialchars($contentSlot->actor) ?>" data-actorlink="<?= htmlspecialchars($contentSlot->actorlink) ?>">
				<? if ( $isAllowedToEdit ): ?>
				<a href="#" class="remove"></a>
				<? endif; ?>
				<a href="<?= $contentSlot->getWikiUrl() ?>" title="<?= htmlspecialchars($contentSlot->title) ?>" title="<?= htmlspecialchars($contentSlot->title) ?>">
					<img class="item-image" src="<?= $contentSlot->getImagePath() ?>"/>
				</a>
				<div class="item-card"?>
					<h1 class="item-title sg-main"><a href="<?= $contentSlot->getWikiUrl() ?>" title="<?= htmlspecialchars($contentSlot->title) ?>" title="<?= htmlspecialchars($contentSlot->title) ?>"><?= htmlspecialchars($contentSlot->title) ?></a></h1>
					<? if ( !empty($contentSlot->actor) ): ?>
					<h1 class="item-actor sg-sub">Portrayed by <a href="<?= htmlentities($contentSlot->getActorUrl()) ?>" title="<?= htmlentities($contentSlot->actor) ?>" title="<?= htmlentities($contentSlot->actor) ?>"><?= htmlentities($contentSlot->actor) ?></a></h1>
					<? endif; ?>
					<span class="item-description sg-sub"><?= htmlspecialchars($contentSlot->description) ?></span>
				</div>

			</section>
		<?php endforeach; ?>
		<div class="add-block">
			<div class="main-add-text">
				<img class="add-icon" src="/extensions/wikia/NjordPrototype/images/plus_g.svg">
				<span class="sg-main">Add a character</span>
			</div>
			<div class="add-text sg-sub">Click on
				<span class="add-sub-text">+add a character/span>
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
					<label class="label sg-sub" for="character-name">Full Name</label>
					<input class="input sg-sub character-name" type="text" id="character-name" placeholder="Fill in the character's name" name="charactername"/>
					<label class="label sg-sub" for="character-actor">Played by</label>
					<input class="input sg-sub character-actor" type="text" id="character-actor" placeholder="Fill in the actor that portrays this character" name="characteractor"/>
					<label class="label sg-sub" for="character-description">About</label>
					<textarea class="input sg-sub character-description" type="text" id="character-description" placeholder="Describe the character on the show" name="characterdescription"></textarea>
				</form>
			</div>
		</div>
	</div>
</div>
