<div class="input-group <?= $type ?>" data-type="" data-dependencies="" data-action="">
	<label for="<?= $name ?>"><?= $labelMsg ?></label>
	<input type="text" name="<?= $name ?>" id="<?= $name ?>">
	<ul class="reference-list <?= (!is_array( $list )) ? 'hidden' : ''; ?>">
		<?php
			if ( !is_array( $list ) ) $list = array();
			$pos = 0;
			foreach ($list as $item) {
				echo $app->renderView(
					'SDSVideoMetadataController',
					'referenceItem',
					array(
						'item' => $item,
						'pos' => $pos,
						'propName' => $name,
						'removeBtnMsg' => wfMessage('sdsvideometadata-vc-remove-item')->text()
					)
				);
				$pos = $pos + 1;
			}
		?>
	</ul>
</div>