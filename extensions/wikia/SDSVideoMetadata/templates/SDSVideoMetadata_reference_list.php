<div class="input-group <?= $type ?>">
	<label for="<?= $name ?>"><?= $labelMsg ?></label>
	<ul>
		<?php foreach ($list as $item): ?>
			<li>
				<input type="text" name="<?= $name ?>[]" id="<?= $name ?>" value="<?= $item['name'] ?>">
				<input type="hidden" name="<?= $id ?>[]" value="<?= $item['id'] ?>">
				<button class="secondary remove"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
			</li>
		<?php endforeach; ?>
		<li>
			<input type="text" name="<?= $name ?>[]" id="<?= $name ?>">
			<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
		</li>
	</ul>
	<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
</div>