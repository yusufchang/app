<?php
	$vcObj = array(
		/*
		'videoObject_name' => 'test object',
		'videoObject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper. Maecenas faucibus mollis interdum.',
		'videoObject_datePublished' => '2013.02.19',
		'videoObject_inLanguage' => 'English',
		'videoObject_subTitleLanguage' => 'Polish',
		'vcType' => 'VideoClipCookingVideo',
		'recipe_name' => array(
			array(
				'name' => 'Pyry',
				'id' => '12345'
			),
			array(
				'name' => 'Vegburger',
				'id' => '12345'
			),
			array(
				'name' => 'aaaa',
				'id' => '12345'
			),
		),
		'provider_name' => array(),
		'publisher_name' => array(),
		'track_name' => array(),
		'musicGroup_name' => array(),
		'musicRecording_musicLabel' => array(),
		'videoObject_genre' => array(),
		'about_location' => array(),
		'about_name' => array(),
		'series_name' => array(),
		'season_name' => array(),
		'movie_name' => array(),
		'videoObject_rating' => array(),
		'videoObject_keywords' => array(),
		'videoObject_associatedMedia' => array(),
		'videoObject_setting' => array(),
		'videoObject_isFamilyFriendly' => '',
		'videoObject_contentFormat' => '',
		*/
	);
?>

<?php if ( $isCorrectFile ) { ?>

	<h1><?= wfMsg('sdsvideometadata-header', $file)?></h1>

	<form class="WikiaForm VMDForm" id="VMDForm" method="POST">

		<fieldset>
			<legend><?= wfMsg('sdsvideometadata-common-metadata-legend')?></legend>

			<!-- Title -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_name',
				'required' => true,
				'labelMsg' => wfMsg('sdsvideometadata-vc-title'),
				'value' => isset( $vcObj['videoObject_name'] ) ? $vcObj['videoObject_name'] : null
			)); ?>

			<!-- Description -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_description',
				'textarea' => true,
				'labelMsg' => wfMsg('sdsvideometadata-vc-description'),
				'value' => isset( $vcObj['videoObject_description'] ) ? $vcObj['videoObject_description'] : null
			)); ?>

			<!-- Published date -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_datePublished',
				'labelMsg' => wfMsg('sdsvideometadata-vc-published-date'),
				'value' => isset( $vcObj['videoObject_datePublished'] ) ? $vcObj['videoObject_datePublished'] : null
			)); ?>

			<!-- Language -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_inLanguage',
				'labelMsg' => wfMsg('sdsvideometadata-vc-language'),
				'value' => isset( $vcObj['videoObject_inLanguage'] ) ? $vcObj['videoObject_inLanguage'] : null
			)); ?>

			<!-- Subtitles -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_subTitleLanguage',
				'labelMsg' => wfMsg('sdsvideometadata-vc-subtitles'),
				'value' => isset( $vcObj['videoObject_subTitleLanguage'] ) ? $vcObj['videoObject_subTitleLanguage'] : null
			)); ?>

		</fieldset>

		<!-- Video object type selection -->
		<div class="input-group">
			<label for="vcType"><?= wfMsg('sdsvideometadata-vc-select-type')?>* <small>(<?= wfMsg
				('sdsvideometadata-vc-required') ?>)</small></label>
			<select name="vcType" id="vcType" data-type="<?= isset( $vcObj['vcType'] ) ? $vcObj['vcType'] : '' ?>">
				<option value="" selected="selected">...</option>
				<option value="VideoClipGamingVideo"><?= wfMsg('sdsvideometadata-vc-type-gaming')?></option>
				<option value="VideoClipTVVideo"><?= wfMsg('sdsvideometadata-vc-type-tv')?></option>
				<option value="VideoClipMovieTrailersVideo"><?= wfMsg('sdsvideometadata-vc-type-movie')?></option>
				<option value="VideoClipTravelVideo"><?= wfMsg('sdsvideometadata-vc-type-travel')?></option>
				<option value="VideoClipCookingVideo"><?= wfMsg('sdsvideometadata-vc-type-cooking')?></option>
				<option value="VideoClipCraftVideo"><?= wfMsg('sdsvideometadata-vc-type-craft')?></option>
				<option value="VideoClipMusicVideo"><?= wfMsg('sdsvideometadata-vc-type-music')?></option>
			</select>
		</div>

		<fieldset id="VMDSpecificMD" class="hidden">
			<legend><?= wfMsg('sdsvideometadata-type-specific-metadata-legend')?></legend>

			<!-- Recipe -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipCookingVideo',
				'name' => 'recipe_name',
				'id' => 'recipe_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-recipe'),
				'list' => isset( $vcObj['recipe_name'] ) ? $vcObj['recipe_name'] : null
			)); ?>

			<!-- Distributor -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo',
				'name' => 'provider_name',
				'id' => 'provider_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-distributor'),
				'list' => isset( $vcObj['provider_name'] ) ? $vcObj['provider_name'] : null
			)); ?>

			<!-- Publisher -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo',
				'name' => 'publisher_name',
				'id' => 'publisher_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-publisher'),
				'list' => isset( $vcObj['publisher_name'] ) ? $vcObj['publisher_name'] : null
			)); ?>

			<!-- Song -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'track_name',
				'id' => 'track_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-song'),
				'list' => isset( $vcObj['track_name'] ) ? $vcObj['track_name'] : null
			)); ?>

			<!-- Artist -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'musicGroup_name',
				'id' => 'musicGroup_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-artist'),
				'list' => isset( $vcObj['musicGroup_name'] ) ? $vcObj['musicGroup_name'] : null
			)); ?>

			<!-- Music Label -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'musicRecording_musicLabel',
				'id' => 'musicRecording_musicLabel_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-music-label'),
				'list' => isset( $vcObj['musicRecording_musicLabel'] ) ? $vcObj['musicRecording_musicLabel'] : null
			)); ?>

			<!-- Genre -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipMusicVideo VideoClipCookingVideo
			VideoClipCraftVideo',
				'name' => 'videoObject_genre',
				'id' => 'videoObject_genre_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-genre'),
				'list' => isset( $vcObj['videoObject_genre'] ) ? $vcObj['videoObject_genre'] : null
			)); ?>

			<!-- Location -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo',
				'name' => 'about_location',
				'id' => 'about_location_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-location'),
				'list' => isset( $vcObj['about_location'] ) ? $vcObj['about_location'] : null
			)); ?>

			<!-- Game -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo',
				'name' => 'about_name',
				'id' => 'about_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-game'),
				'list' => isset( $vcObj['about_name'] ) ? $vcObj['about_name'] : null
			)); ?>

			<!-- TV Series -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTVVideo',
				'name' => 'series_name',
				'id' => 'series_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-series'),
				'list' => isset( $vcObj['series_name'] ) ? $vcObj['series_name'] : null
			)); ?>

			<!-- Season -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTVVideo',
				'name' => 'season_name',
				'id' => 'season_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-season'),
				'list' => isset( $vcObj['season_name'] ) ? $vcObj['season_name'] : null
			)); ?>

			<!-- Movie -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMovieTrailersVideo',
				'name' => 'movie_name',
				'id' => 'movie_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-movie'),
				'list' => isset( $vcObj['movie_name'] ) ? $vcObj['movie_name'] : null
			)); ?>

			<!-- Trailer rating  -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'type' => 'VideoClipMovieTrailersVideo',
				'name' => 'videoObject_rating',
				'labelMsg' => wfMsg('sdsvideometadata-vc-trailer-rating'),
				'value' => isset( $vcObj['videoObject_rating'] ) ? $vcObj['videoObject_rating'] : null
			)); ?>

			<!-- Type -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo VideoClipTVVideo',
				'name' => 'videoObject_keywords',
				'id' => 'videoObject_keywords_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-kind'),
				'list' => isset( $vcObj['videoObject_keywords'] ) ? $vcObj['videoObject_keywords'] : null
			)); ?>

			<!-- Age gate -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'select', array(
				'type' => 'VideoClipGamingVideo VideoClipMovieTrailersVideo',
				'name' => 'videoObject_isFamilyFriendly',
				'labelMsg' => wfMsg('sdsvideometadata-vc-age-gate'),
				'options' => array(
					array(
						'value' => 'true',
						'text' => wfMsg('sdsvideometadata-vc-boolean-true')
					),
					array(
						'value' => 'false',
						'text' => wfMsg('sdsvideometadata-vc-boolean-false')
					)
				),
				'selected' => isset( $vcObj['videoObject_isFamilyFriendly'] ) ? $vcObj['videoObject_isFamilyFriendly'] : null
			)); ?>

			<!-- PAL -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'select', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'videoObject_contentFormat',
				'labelMsg' => wfMsg('sdsvideometadata-vc-pal'),
				'options' => array(
					array(
						'value' => 'PAL',
						'text' => wfMsg('sdsvideometadata-vc-boolean-true')
					),
					array(
						'value' => '',
						'text' => wfMsg('sdsvideometadata-vc-boolean-false')
					)
				),
				'selected' => isset( $vcObj['videoObject_contentFormat'] ) ? $vcObj['videoObject_contentFormat'] : null
			)); ?>

			<!-- Soundtrack -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo',
				'name' => 'videoObject_associatedMedia',
				'id' => 'videoObject_associatedMedia_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-soundtrack'),
				'list' => isset( $vcObj['videoObject_associatedMedia'] ) ? $vcObj['videoObject_associatedMedia'] : null
			)); ?>

			<!-- Setting -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'literal_list', array(
				'type' => 'VideoClipGamingVideo VideoClipMusicVideo VideoClipTVVideo VideoClipMovieTrailersVideo',
				'name' => 'videoObject_setting',
				'id' => 'videoObject_setting_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-setting'),
				'list' => isset( $vcObj['videoObject_setting'] ) ? $vcObj['videoObject_setting'] : null
			)); ?>

		</fieldset>

		<label for="vcCompleted">
			<?= wfMsg('sdsvideometadata-vc-finished-flag')?>
			<input type="checkbox" name="vcCompleted" id="vcCompleted" value="1" <?= !empty($isCompleted) ? "checked" : "";?> >
		</label>

		<?php if (!empty($wasPasted)): ?>
			<p><?= (isset($success) && $success === true ) ?  wfMsg('sdsvideometadata-vc-save') : $errorMessage ?></p>
		<?php endif; ?>

		<input type="submit" id="VMDFormSave" value="<?= wfMsg('sdsvideometadata-save')?>" disabled="disabled">

	</form>
<?php } else { ?>
	<?= wfMsg('sdsvideometadata-error-no-video-file')?>
<?php } ?>
