<?php
	$vcObj = array(
		'videoObject_name' => 'test object',
		'videoObject_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper. Maecenas faucibus mollis interdum.',
		'videoObject_datePublished' => '2013.02.19',
		'videoObject_inLanguage' => 'English',
		'videoObject_subTitleLanguage' => 'Polish',
		'vcType' => 'VideoClipCookingVideo',
		'vcRecipe' => array(
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
				'value' => $vcObj['videoObject_name']
			)); ?>

			<!-- Description -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_description',
				'textarea' => true,
				'labelMsg' => wfMsg('sdsvideometadata-vc-description'),
				'value' => $vcObj['videoObject_description']
			)); ?>

			<!-- Published date -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_datePublished',
				'labelMsg' => wfMsg('sdsvideometadata-vc-published-date'),
				'value' => $vcObj['videoObject_datePublished']
			)); ?>

			<!-- Language -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_inLanguage',
				'labelMsg' => wfMsg('sdsvideometadata-vc-language'),
				'value' => $vcObj['videoObject_inLanguage']
			)); ?>

			<!-- Subtitles -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'name' => 'videoObject_subTitleLanguage',
				'labelMsg' => wfMsg('sdsvideometadata-vc-subtitles'),
				'value' => $vcObj['videoObject_subTitleLanguage']
			)); ?>

		</fieldset>

		<!-- Video object type selection -->
		<div class="input-group">
			<label for="vcType"><?= wfMsg('sdsvideometadata-vc-select-type')?>* <small>(<?= wfMsg
				('sdsvideometadata-vc-required') ?>)</small></label>
			<select name="vcType" id="vcType" data-type="<?= $vcObj['vcType'] ?>">
				<option value="">...</option>
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
				'list' => $vcObj['vcRecipe']
			)); ?>

			<!-- Distributor -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo',
				'name' => 'provider_name',
				'id' => 'provider_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-distributor'),
				'list' => array()
			)); ?>

			<!-- Publisher -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipCookingVideo VideoClipCraftVideo',
				'name' => 'publisher_name',
				'id' => 'publisher_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-publisher'),
				'list' => array()
			)); ?>

			<!-- Song -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'track_name',
				'id' => 'track_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-song'),
				'list' => array()
			)); ?>

			<!-- Artist -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'musicGroup_name',
				'id' => 'musicGroup_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-artist'),
				'list' => array()
			)); ?>

			<!-- Music Label -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMusicVideo',
				'name' => 'musicRecording_musicLabel',
				'id' => 'musicRecording_musicLabel_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-music-label'),
				'list' => array()
			)); ?>

			<!-- Genre -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo VideoClipMusicVideo VideoClipCookingVideo
			VideoClipCraftVideo',
				'name' => 'videoObject_genre',
				'id' => 'videoObject_genre_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-genre'),
				'list' => array()
			)); ?>

			<!-- Location -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTravelVideo',
				'name' => 'about_location',
				'id' => 'about_location_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-location'),
				'list' => array()
			)); ?>

			<!-- Game -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo',
				'name' => 'about_name',
				'id' => 'about_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-game'),
				'list' => array()
			)); ?>

			<!-- TV Series -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTVVideo',
				'name' => 'series_name',
				'id' => 'series_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-series'),
				'list' => array()
			)); ?>

			<!-- Season -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipTVVideo',
				'name' => 'season_name',
				'id' => 'season_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-season'),
				'list' => array()
			)); ?>

			<!-- Movie -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipMovieTrailersVideo',
				'name' => 'movie_name',
				'id' => 'movie_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-movie'),
				'list' => array()
			)); ?>

			<!-- Trailer rating  -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'default', array(
				'type' => 'VideoClipMovieTrailersVideo',
				'name' => 'videoObject_rating',
				'labelMsg' => wfMsg('sdsvideometadata-vc-trailer-rating')
			)); ?>

			<!-- Type -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo VideoClipTVVideo',
				'name' => 'videoObject_keywords',
				'id' => 'videoObject_keywords_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-kind'),
				'list' => array()
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
				)
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
				)
			)); ?>

			<!-- Soundtrack -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo',
				'name' => 'videoObject_associatedMedia',
				'id' => 'videoObject_associatedMedia_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-soundtrack'),
				'list' => array()
			)); ?>

			<!-- Setting -->
			<?= F::app()->renderPartial('SDSVideoMetadataController', 'reference_list', array(
				'type' => 'VideoClipGamingVideo VideoClipMusicVideo VideoClipTVVideo VideoClipMovieTrailersVideo',
				'name' => 'videoObject_setting',
				'id' => 'videoObject_setting_id',
				'labelMsg' => wfMsg('sdsvideometadata-vc-setting'),
				'list' => array()
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
