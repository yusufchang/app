<?php if ( $isCorrectFile ) { ?>
	<h1><?= wfMsg('sdsvideometadata-header', $file)?></h1>
	<form class="WikiaForm VMDForm" id="VMDForm" method="POST">
		<fieldset>
			<legend><?= wfMsg('sdsvideometadata-common-metadata-legend')?></legend>
			<div class="input-group">
				<label for="vcTitle"><?= wfMsg('sdsvideometadata-vc-title')?>* <small>(<?= wfMsg
				('sdsvideometadata-vc-required')?>)</small></label>
				<input type="text" name="videoObject_name" id="vcTitle">
			</div>
			<div class="input-group">
				<label for="vcDescription"><?= wfMsg('sdsvideometadata-vc-description')?></label>
				<textarea name="videoObject_description" id="vcDescription"></textarea>
			</div>
			<div class="input-group">
				<label for="vcPublishedDate"><?= wfMsg('sdsvideometadata-vc-published-date')?></label>
				<input type="text" name="videoObject_datePublished" id="vcPublishedDate">
			</div>
			<div class="input-group">
				<label for="vcLanguage"><?= wfMsg('sdsvideometadata-vc-language')?></label>
				<input type="text" name="videoObject_inLanguage" id="vcLanguage">
			</div>
			<div class="input-group">
				<label for="vcSubtitles"><?= wfMsg('sdsvideometadata-vc-subtitles')?></label>
				<input type="text" name="videoObject_subTitleLanguage" id="vcSubtitles">
			</div>
		</fieldset>

		<div class="input-group">
			<label for="vcType"><?= wfMsg('sdsvideometadata-vc-select-type')?></label>
			<select name="vcType" id="vcType">
				<option value="">...</option>
				<option value="VideoClipGamingVideo"><?= wfMsg('sdsvideometadata-vc-type-gaming')?></option>
				<option value="VideoClipTVVideo"><?= wfMsg('sdsvideometadata-vc-type-tv')?></option>
				<option value="VideoClipMovieTrailersVideo"><?= wfMsg('sdsvideometadata-vc-type-movie')?></option>
				<option value="VideoClipTravelVideos"><?= wfMsg('sdsvideometadata-vc-type-travel')?></option>
				<option value="VideoClipCookingVideos"><?= wfMsg('sdsvideometadata-vc-type-cooking')?></option>
				<option value="VideoClipCraftVideos"><?= wfMsg('sdsvideometadata-vc-type-craft')?></option>
				<option value="VideoClipMusicVideos"><?= wfMsg('sdsvideometadata-vc-type-music')?></option>
			</select>
		</div>

		<fieldset id="VMDSpecificMD" class="hidden">
			<legend><?= wfMsg('sdsvideometadata-type-specific-metadata-legend')?></legend>
			<div class="input-group VideoClipCookingVideos">
				<label for="vcRecipe"><?= wfMsg('sdsvideometadata-vc-recipe')?></label>
				<ul>
					<li>
						<input type="text" name="recipe_name[]" id="vcRecipe">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipTravelVideos VideoClipCookingVideos VideoClipCraftVideos">
				<label for="vcDistributor"><?= wfMsg('sdsvideometadata-vc-distributor')?></label>
				<ul>
					<li>
						<input type="text" name="schema:provider[]" id="vcDistributor">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipTravelVideos VideoClipCookingVideos VideoClipCraftVideos">
				<label for="vcPublisher"><?= wfMsg('sdsvideometadata-vc-publisher')?></label>
				<ul>
					<li>
						<input type="text" name="schema:publisher[]" id="vcPublisher">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipMusicVideos">
				<label for="vcSong"><?= wfMsg('sdsvideometadata-vc-song')?></label>
				<ul>
					<li>
						<input type="text" name="schema:track[]" id="vcSong">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipMusicVideos">
				<label for="vcArtist"><?= wfMsg('sdsvideometadata-vc-artist')?></label>
				<ul>
					<li>
						<input type="text" name="schema:MusicGroup[]" id="vcArtist">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipMusicVideos">
				<label for="vcMusicLabel"><?= wfMsg('sdsvideometadata-vc-music-label')?></label>
				<ul>
					<li>
						<input type="text" name="schema:Organization[]" id="vcMusicLabel">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipTravelVideos VideoClipMusicVideos VideoClipCookingVideos
			VideoClipCraftVideos">
				<label for="vcGenre"><?= wfMsg('sdsvideometadata-vc-genre')?></label>
				<ul>
					<li>
						<input type="text" name="schema:genre[]" id="vcGenre">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipTravelVideos">
				<label for="vcLocation"><?= wfMsg('sdsvideometadata-vc-location')?></label>
				<ul>
					<li>
						<input type="text" name="schema:contentLocation[]" id="vcLocation">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipGamingVideo">
				<label for="vcGame"><?= wfMsg('sdsvideometadata-vc-game')?></label>
				<ul>
					<li>
						<input type="text" name="about_name[]" id="vcGame">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipTVVideo">
				<label for="vcSeries"><?= wfMsg('sdsvideometadata-vc-series')?></label>
				<ul>
					<li>
						<input type="text" name="series_name[]" id="vcSeries">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipTVVideo">
				<label for="vcSeason"><?= wfMsg('sdsvideometadata-vc-season')?></label>
				<ul>
					<li>
						<input type="text" name="season_name[]" id="vcSeason">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipMovieTrailersVideo">
				<label for="vcMovie"><?= wfMsg('sdsvideometadata-vc-movie')?></label>
				<ul>
					<li>
						<input type="text" name="movie_name[]" id="vcMovie">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipMovieTrailersVideo">
				<label for="vcTrailerRating"><?= wfMsg('sdsvideometadata-vc-trailer-rating')?></label>
				<input type="text" name="videoObject_rating" id="vcTrailerRating">
			</div>
			<div class="input-group VideoClipGamingVideo VideoClipTVVideo">
				<label for="vcKind"><?= wfMsg('sdsvideometadata-vc-kind')?></label>
				<ul>
					<li>
						<input type="text" name="videoObject_keywords[]" id="vcKind">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipGamingVideo VideoClipMovieTrailersVideo">
				<label for="vcAgeGate"><?= wfMsg('sdsvideometadata-vc-age-gate')?></label>
				<select name="videoObject_isFamilyFriendly" id="vcAgeGate">
					<option value="true"><?= wfMsg('sdsvideometadata-vc-boolean-true')?></option>
					<option value="false"><?= wfMsg('sdsvideometadata-vc-boolean-false')?></option>
				</select>
			</div>
			<div class="input-group VideoClipMusicVideos">
				<label for="vcPAL"><?= wfMsg('sdsvideometadata-vc-pal')?></label>
				<select name="schema:contentFormat" id="vcPAL">
					<option value="true"><?= wfMsg('sdsvideometadata-vc-boolean-true')?></option>
					<option value="false"><?= wfMsg('sdsvideometadata-vc-boolean-false')?></option>
				</select>
			</div>
			<div class="input-group VideoClipGamingVideo">
				<label for="vcSoundtrack"><?= wfMsg('sdsvideometadata-vc-soundtrack')?></label>
				<ul>
					<li>
						<input type="text" name="videoObject_associatedMedia[]" id="vcSoundtrack">
						<button class="add secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>

			<div class="input-group VideoClipGamingVideo VideoClipMusicVideos VideoClipTVVideo VideoClipMovieTrailersVideo">
				<label for="vcSetting"><?= wfMsg('sdsvideometadata-vc-setting')?></label>
				<ul>
					<li>
						<input type="text" name="videoObject_setting[]" id="vcSetting">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
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
