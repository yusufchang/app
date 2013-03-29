<div class="VMD-wrapper">
	<?php if ( $isCorrectFile ) { ?>
		<h1 class="VMD-header"><?= $file ?></h1>
		<form class="WikiaForm VMD-form" id="VMDForm" method="POST">

			<fieldset>
				<legend class="VMD-header"><?= wfMessage('sdsvideometadata-common-metadata-legend')->text() ?></legend>

				<!-- Description -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_description',
				'textarea' => true,
				'labelMsg' => wfMessage('sdsvideometadata-vc-description')->text(),
				'value' => isset( $vcObj['videoObject_description'] ) ? $vcObj['videoObject_description'] : null
				)); ?>

				<!-- Language -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_inLanguage',
				'labelMsg' => wfMessage('sdsvideometadata-vc-language')->text(),
				'value' => isset( $vcObj['videoObject_inLanguage'] ) ? $vcObj['videoObject_inLanguage'] : null
				)); ?>

				<!-- Subtitles -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_subTitleLanguage',
				'labelMsg' => wfMessage('sdsvideometadata-vc-subtitles')->text(),
				'value' => isset( $vcObj['videoObject_subTitleLanguage'] ) ? $vcObj['videoObject_subTitleLanguage'] : null
				)); ?>

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
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipCookingVideo',
				'name' => 'recipe_name',
				'id' => 'recipe_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-recipe')->text(),
				'list' => isset( $vcObj['recipe_name'] ) ? $vcObj['recipe_name'] : null
				)); ?>

				<!-- Distributor -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo VideoClipHowToVideo',
				'name' => 'provider_name',
				'id' => 'provider_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-distributor')->text(),
				'list' => isset( $vcObj['provider_name'] ) ? $vcObj['provider_name'] : null
				)); ?>

				<!-- Publisher -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo VideoClipHowToVideo',
				'name' => 'publisher_name',
				'id' => 'publisher_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-publisher')->text(),
				'list' => isset( $vcObj['publisher_name'] ) ? $vcObj['publisher_name'] : null
				)); ?>

				<!-- Song -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'track_name',
				'id' => 'track_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-song')->text(),
				'list' => isset( $vcObj['track_name'] ) ? $vcObj['track_name'] : null
				)); ?>

				<!-- Artist -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'musicGroup_name',
				'id' => 'musicGroup_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-artist')->text(),
				'list' => isset( $vcObj['musicGroup_name'] ) ? $vcObj['musicGroup_name'] : null
				)); ?>

				<!-- Music Label -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'musicRecording_musicLabel',
				'id' => 'musicRecording_musicLabel_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-music-label')->text(),
				'list' => isset( $vcObj['musicRecording_musicLabel'] ) ? $vcObj['musicRecording_musicLabel'] : null
				)); ?>

				<!-- Genre -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'literal_list', array(
				'type' => 'VideoClipTravelVideo VideoClipMusicVideo VideoClipCookingVideo
						VideoClipCraftVideo VideoClipHowToVideo',
				'name' => 'videoObject_genre',
				'id' => 'videoObject_genre_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-genre')->text(),
				'list' => isset( $vcObj['videoObject_genre'] ) ? $vcObj['videoObject_genre'] : null
				)); ?>

				<!-- Location -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo',
				'name' => 'about_location',
				'id' => 'about_location_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-location')->text(),
				'list' => isset( $vcObj['about_location'] ) ? $vcObj['about_location'] : null
				)); ?>

				<!-- Game -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo',
				'name' => 'about_name',
				'id' => 'about_id',
				'suggestionsType' => 'game',
				'labelMsg' => wfMessage('sdsvideometadata-vc-game')->text(),
				'list' => isset( $vcObj['about_name'] ) ? $vcObj['about_name'] : null
				)); ?>

				<!-- TV Series -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTVVideo',
				'name' => 'series_name',
				'id' => 'series_id',
				'suggestionsType' => 'tv_series',
				'labelMsg' => wfMessage('sdsvideometadata-vc-series')->text(),
				'list' => isset( $vcObj['series_name'] ) ? $vcObj['series_name'] : null
				)); ?>

				<!-- Season -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTVVideo',
				'name' => 'season_name',
				'id' => 'season_id',
				'suggestionsType' => 'tv_season',
				'labelMsg' => wfMessage('sdsvideometadata-vc-season')->text(),
				'list' => isset( $vcObj['season_name'] ) ? $vcObj['season_name'] : null
				)); ?>

				<!-- Movie -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMovieTrailersVideo',
				'name' => 'movie_name',
				'id' => 'movie_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-movie')->text(),
				'list' => isset( $vcObj['movie_name'] ) ? $vcObj['movie_name'] : null
				)); ?>

				<!-- Trailer rating  -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'type' => 'VideoClipMovieTrailersVideo',
				'name' => 'videoObject_rating',
				'labelMsg' => wfMessage('sdsvideometadata-vc-trailer-rating')->text(),
				'value' => isset( $vcObj['videoObject_rating'] ) ? $vcObj['videoObject_rating'] : null
				)); ?>

				<!-- Type -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'literal_list', array(
				'type' => 'VideoClipGamingVideo VideoClipTVVideo',
				'name' => 'videoObject_keywords',
				'id' => 'videoObject_keywords_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-kind')->text(),
				'list' => isset( $vcObj['videoObject_keywords'] ) ? $vcObj['videoObject_keywords'] : null
				)); ?>

				<!-- Age gate -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'select', array(
				'type' => 'VideoClipGamingVideo VideoClipMovieTrailersVideo',
				'name' => 'videoObject_isFamilyFriendly',
				'labelMsg' => wfMessage('sdsvideometadata-vc-age-gate')->text(),
				'options' => array(
					array(
						'value' => '',
						'text' => wfMessage('sdsvideometadata-vc-boolean-not-set')->text()
					),
					array(
						'value' => 'true',
						'text' => wfMessage('sdsvideometadata-vc-boolean-true')->text()
					),
					array(
						'value' => 'false',
						'text' => wfMessage('sdsvideometadata-vc-boolean-false')->text()
					)
				),
				'selected' => isset( $vcObj['videoObject_isFamilyFriendly'] ) ? $vcObj['videoObject_isFamilyFriendly'] : null
				)); ?>

				<!-- PAL -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'select', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'videoObject_contentFormat',
				'labelMsg' => wfMessage('sdsvideometadata-vc-pal')->text(),
				'options' => array(
					array(
						'value' => '',
						'text' => wfMessage('sdsvideometadata-vc-boolean-not-set')->text()
					),
					array(
						'value' => 'PAL',
						'text' => wfMessage('sdsvideometadata-vc-boolean-true')->text()
					)
				),
				'selected' => isset( $vcObj['videoObject_contentFormat'] ) ? $vcObj['videoObject_contentFormat'] : null
				)); ?>

				<!-- Soundtrack -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo',
				'name' => 'videoObject_associatedMedia',
				'id' => 'videoObject_associatedMedia_id',
				'suggestionsType' => 'music_recording',
				'labelMsg' => wfMessage('sdsvideometadata-vc-soundtrack')->text(),
				'list' => isset( $vcObj['videoObject_associatedMedia'] ) ? $vcObj['videoObject_associatedMedia'] : null
				)); ?>

				<!-- Setting -->
				<?= F::app()->renderPartial('SDSVideoMetadataController', 'literal_list', array(
				'type' => 'VideoClipGamingVideo VideoClipMusicVideo VideoClipTVVideo VideoClipMovieTrailersVideo',
				'name' => 'videoObject_setting',
				'id' => 'videoObject_setting_id',
				'labelMsg' => wfMessage('sdsvideometadata-vc-setting')->text(),
				'list' => isset( $vcObj['videoObject_setting'] ) ? $vcObj['videoObject_setting'] : null
				)); ?>

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

