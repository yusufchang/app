<div class="global-edit-wrap">
	<a href="<?= $editLink ?>" <?php if ( $editor == 2 ): ?>data-id="ve-edit" id="ca-ve-edit"<?php endif; ?>>
		<?php if ( !$source ) : ?>
			<img class="global-edit-btn" src="/extensions/wikia/NjordPrototype/images/pencil_b.svg"/>
		<?php endif; ?>
		<span class="edit-link sg-main" href=""><?= $name ?></span>
	</a>
</div>
