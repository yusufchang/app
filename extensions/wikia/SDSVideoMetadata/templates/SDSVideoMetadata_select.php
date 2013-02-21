<div class="input-group <?= $type ?>">
	<label for="<?= $name ?>"><?= $labelMsg ?></label>
	<select name="<?= $name ?>" id="<?= $name ?>">
		<?php foreach ($options as $option): ?>
			<option value="<?= $option['value'] ?>" <?= (!empty($selected) && ( $selected === $option['value'] ) ) ?
				'selected="selected"' : '' ?>><?= $option['text'] ?></option>
		<? endforeach; ?>
	</select>
</div>