<div class="VMD-wrapper">
	<?php if ( $isCorrectFile ) { ?>
		<h1 class="VMD-header"><?= $file ?></h1>
		<form class="WikiaForm VMD-form" id="VMDForm" method="POST">

			<fieldset>
				<legend class="VMD-header"><?= wfMessage('sdsvideometadata-common-metadata-legend')->text() ?></legend>

				<!-- Title -->
	<!--			--><?//= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
	//			'name' => 'videoObject_name',
	//			'required' => true,
	//			'labelMsg' => wfMessage('sdsvideometadata-vc-title'),
	//			'value' => isset( $vcObj['videoObject_name'] ) ? $vcObj['videoObject_name'] : null
	//			)); ?>

				<!-- Description -->
				<?= $formBuilder->renderField( 'videoObject_description' ); ?>

<!--				--><?//= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
//				'name' => 'videoObject_description',
//				'textarea' => true,
//				'labelMsg' => wfMessage('sdsvideometadata-vc-description')->text(),
//				'value' => isset( $vcObj['videoObject_description'] ) ? $vcObj['videoObject_description'] : null
//				)); ?>

				<!-- Language -->
				<?= $formBuilder->renderField( 'videoObject_inLanguage' ); ?>

<!--				--><?//= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
//				'name' => 'videoObject_inLanguage',
//				'labelMsg' => wfMessage('sdsvideometadata-vc-language')->text(),
//				'value' => isset( $vcObj['videoObject_inLanguage'] ) ? $vcObj['videoObject_inLanguage'] : null
//				)); ?>

				<!-- Subtitles -->
				<?= $formBuilder->renderField( 'videoObject_subTitleLanguage' ); ?>

<!--				--><?//= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
//				'name' => 'videoObject_subTitleLanguage',
//				'labelMsg' => wfMessage('sdsvideometadata-vc-subtitles')->text(),
//				'value' => isset( $vcObj['videoObject_subTitleLanguage'] ) ? $vcObj['videoObject_subTitleLanguage'] : null
//				)); ?>

				<!-- Video object type selection -->
				<div class="input-group">
					<label for="vcType"><?= wfMessage('sdsvideometadata-vc-select-type')->text() ?> <small>(<?=
						wfMessage
					('sdsvideometadata-vc-required') ?>)</small></label>
					<select name="vcType" id="vcType" data-type="<?= isset( $vcObj['vcType'] ) ? $vcObj['vcType'] : '' ?>">
						<option value="" selected="selected"><?= wfMessage('sdsvideometadata-vc-type-select')->text()
							?></option>
						<option value="VideoClipGamingVideo"><?= wfMessage('sdsvideometadata-vc-type-gaming')->text() ?></option>
						<option value="VideoClipTVVideo"><?= wfMessage('sdsvideometadata-vc-type-tv')->text() ?></option>
						<option value="VideoClipMovieTrailersVideo"><?= wfMessage('sdsvideometadata-vc-type-movie')->text() ?></option>
						<option value="VideoClipTravelVideo"><?= wfMessage('sdsvideometadata-vc-type-travel')->text() ?></option>
						<option value="VideoClipCookingVideo"><?= wfMessage('sdsvideometadata-vc-type-cooking')->text() ?></option>
						<option value="VideoClipCraftVideo"><?= wfMessage('sdsvideometadata-vc-type-craft')->text() ?></option>
						<option value="VideoClipHowToVideo"><?= wfMessage('sdsvideometadata-vc-type-how-to')->text() ?></option>
						<option value="VideoClipMusicVideo"><?= wfMessage('sdsvideometadata-vc-type-music')->text() ?></option>
					</select>
				</div>

			</fieldset>

			<fieldset id="VMDSpecificMD" class="hidden VMD-details">
				<legend class="VMD-header"><?= wfMessage('sdsvideometadata-type-specific-metadata-legend')->text() ?></legend>

				<!-- Recipe -->
				<?= $formBuilder->renderField( 'recipe_name' ); ?>
<!--				--><?//= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
//				'type' => 'VideoClipCookingVideo',
//				'name' => 'recipe_name',
//				'id' => 'recipe_id',
//				'labelMsg' => wfMessage('sdsvideometadata-vc-recipe')->text(),
//				'list' => isset( $vcObj['recipe_name'] ) ? $vcObj['recipe_name'] : null
//				)); ?>

				<!-- Distributor -->
				<?= $formBuilder->renderField( 'provider_name' ); ?>
<!--				--><?//= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
//				'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo VideoClipHowToVideo',
//				'name' => 'provider_name',
//				'id' => 'provider_id',
//				'labelMsg' => wfMessage('sdsvideometadata-vc-distributor')->text(),
//				'list' => isset( $vcObj['provider_name'] ) ? $vcObj['provider_name'] : null
//				)); ?>

				<!-- Publisher -->
				<?= $formBuilder->renderField( 'publisher_name' ); ?>

				<!-- Song -->
				<?= $formBuilder->renderField( 'track_name' ); ?>

				<!-- Artist -->
				<?= $formBuilder->renderField( 'musicGroup_name' ); ?>

				<!-- Music Label -->
				<?= $formBuilder->renderField( 'musicRecording_musicLabel' ); ?>

				<!-- Genre -->
				<?= $formBuilder->renderField( 'videoObject_genre' ); ?>

				<!-- Location -->
				<?= $formBuilder->renderField( 'about_location' ); ?>

				<!-- Game -->
				<?= $formBuilder->renderField( 'about_name' ); ?>

				<!-- TV Series -->
				<?= $formBuilder->renderField( 'series_name' ); ?>

				<!-- Season -->
				<?= $formBuilder->renderField( 'season_name' ); ?>

				<!-- Movie -->
				<?= $formBuilder->renderField( 'movie_name' ); ?>

				<!-- Trailer rating  -->
				<?= $formBuilder->renderField( 'videoObject_rating' ); ?>

				<!-- Type -->
				<?= $formBuilder->renderField( 'videoObject_keywords' ); ?>

				<!-- Age gate -->
				<?= $formBuilder->renderField( 'videoObject_isFamilyFriendly' ); ?>

				<!-- PAL -->
				<?= $formBuilder->renderField( 'videoObject_contentFormat' ); ?>

				<!-- Soundtrack -->
				<?= $formBuilder->renderField( 'videoObject_associatedMedia' ); ?>

				<!-- Setting -->
				<?= $formBuilder->renderField( 'videoObject_setting' ); ?>

			</fieldset>

			<div class="input-group">
				<label for="vcCompleted">
					<?= wfMessage('sdsvideometadata-vc-finished-flag')->text() ?>
					<input type="checkbox" name="vcCompleted" id="vcCompleted" value="1" <?= !empty($isCompleted) ? "checked" : "";?> >
				</label>
			</div>

			<?php if (!empty($wasPasted)): ?>
				<p><?= (isset($success) && $success === true ) ?  wfMessage('sdsvideometadata-vc-save')->text() : $errorMessage ?></p>
			<?php endif; ?>

			<input type="submit" id="VMDFormSave" value="<?= wfMessage('sdsvideometadata-save')->text() ?>"
			       disabled="disabled">
			<button id="VMDSkip" class="secondary">Skip</button>

		</form>
		<div id="VMD-player-wrapper" class="VMD-player-wrapper">
			<div>
				<?= isset($embedCode) ? $embedCode : "" ?>
			</div>
		</div>
	<?php } else { ?>
		<?= wfMessage('sdsvideometadata-error-no-video-file')->text() ?>
	<?php } ?>
</div>

