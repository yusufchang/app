<?php if ( $isCorrectFile ) { ?>
	<h1><?= wfMsg('sdsvideometadata-header', $file)?></h1>
	<form class="WikiaForm VMDForm" id="VMDForm" method="POST">
		<fieldset>
			<legend><?= wfMsg('sdsvideometadata-common-metadata-legend')?></legend>
			<div class="input-group">
				<label for="vcTitle"><?= wfMsg('sdsvideometadata-vc-title')?></label>
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
				<option value=""><?= wfMsg('sdsvideometadata-vc-type-tv')?></option>
				<option value=""><?= wfMsg('sdsvideometadata-vc-type-movie')?></option>
			</select>
		</div>

		<fieldset id="VMDSpecificMD" class="hidden">
			<legend><?= wfMsg('sdsvideometadata-type-specific-metadata-legend')?></legend>
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
			<div class="input-group tvVideos">
				<label for="vcSeries"><?= wfMsg('sdsvideometadata-vc-series')?></label>
				<ul>
					<li>
						<input type="text" name="schema:Thing[]" id="vcSeries">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group tvVideos">
				<label for="vcSeason"><?= wfMsg('sdsvideometadata-vc-season')?></label>
				<ul>
					<li>
						<input type="text" name="schema:Season[]" id="vcSeason">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group movieTrailerVideos">
				<label for="vcMovie"><?= wfMsg('sdsvideometadata-vc-movie')?></label>
				<ul>
					<li>
						<input type="text" name="schema:about[]" id="vcMovie">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group movieTrailerVideos">
				<label for="vcTrailerRating"><?= wfMsg('sdsvideometadata-vc-trailer-rating')?></label>
				<input type="text" name="schema:contentRating" id="vcTrailerRating">
			</div>
			<div class="input-group VideoClipGamingVideo tvVideos">
				<label for="vcKind"><?= wfMsg('sdsvideometadata-vc-kind')?></label>
				<ul>
					<li>
						<input type="text" name="videoObject_keywords[]" id="vcKind">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group VideoClipGamingVideo movieTrailerVideos">
				<label for="vcAgeGate"><?= wfMsg('sdsvideometadata-vc-age-gate')?></label>
				<select name="videoObject_isFamilyFriendly" id="vcAgeGate">
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

			<div class="input-group VideoClipGamingVideo tvVideos movieTrailerVideos">
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
		<input type="submit" value="<?= wfMsg('sdsvideometadata-save')?>">
	</form>
<?php } else { ?>
	<?= wfMsg('sdsvideometadata-error-no-video-file')?>
<?php } ?>
