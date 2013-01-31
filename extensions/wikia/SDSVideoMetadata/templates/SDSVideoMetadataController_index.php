<?php if ( $isCorrectFile ) { ?>
	<h1><?= wfMsg('sdsvideometadata-header', array('parseinline'),
		$file)?></h1>
	<form class="WikiaForm VMDForm" id="VMDForm" method="POST">
		<fieldset>
			<legend><?= wfMsg('sdsvideometadata-common-metadata-legend')?></legend>
			<div class="input-group">
				<label for="vcTitle"><?= wfMsg('sdsvideometadata-vc-title')?></label>
				<input type="text" name="schema:name" id="vcTitle">
			</div>
			<div class="input-group">
				<label for="vcDescription"><?= wfMsg('sdsvideometadata-vc-description')?></label>
				<textarea name="schema:description" id="vcDescription"></textarea>
			</div>
			<div class="input-group">
				<label for="vcPublishedDate"><?= wfMsg('sdsvideometadata-vc-published-date')?></label>
				<input type="text" name="schema:datePublished" id="vcPublishedDate">
			</div>
			<div class="input-group">
				<label for="vcLanguage"><?= wfMsg('sdsvideometadata-vc-language')?></label>
				<input type="text" name="schema:inLanguage" id="vcLanguage">
			</div>
			<div class="input-group">
				<label for="vcSubtitles"><?= wfMsg('sdsvideometadata-vc-subtitles')?></label>
				<input type="text" name="schema:subTitleLanguage" id="vcSubtitles">
			</div>
		</fieldset>
		<fieldset>
			<legend><?= wfMsg('sdsvideometadata-type-specific-metadata-legend')?></legend>

			<input type="hidden" name="vcType" value="gamingVideos">

			<div class="input-group gamingVideos">
				<label for="vcGame"><?= wfMsg('sdsvideometadata-vc-game')?></label>
				<ul>
					<li>
						<input type="text" name="schema:name[]" id="vcGame">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
			<div class="input-group gamingVideos">
				<label for="vcKind"><?= wfMsg('sdsvideometadata-vc-kind')?></label>
				<ul>
					<li>
						<input type="text" name="?????" id="vcKind">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>

			<div class="input-group gamingVideos">
				<label for="vcAgregate"><?= wfMsg('sdsvideometadata-vc-agregate')?></label>
				<select name="schema:isFamilyFriendly" id="vcAgregate">
					<option><?= wfMsg('sdsvideometadata-vc-boolean-not-set')?></option>
					<option value="true"><?= wfMsg('sdsvideometadata-vc-boolean-true')?></option>
					<option value="false"><?= wfMsg('sdsvideometadata-vc-boolean-false')?></option>
				</select>
			</div>
			<div class="input-group gamingVideos">
				<label for="vcSoundtrack"><?= wfMsg('sdsvideometadata-vc-soundtrack')?></label>
				<ul>
					<li>
						<input type="text" name="schema:associatedMedia[]" id="vcSoundtrack">
						<button class="add secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>

			<div class="input-group gamingVideos">
				<label for="vcSetting"><?= wfMsg('sdsvideometadata-vc-setting')?></label>
				<ul>
					<li>
						<input type="text" name="wikia:setting[]" id="vcSetting">
						<button class="secondary remove hidden"><?= wfMsg('sdsvideometadata-vc-remove-item')?></button>
					</li>
				</ul>
				<button class="add secondary"><?= wfMsg('sdsvideometadata-vc-add-item')?></button>
			</div>
		</fieldset>
		<label for="vcCompleted">
			<?= wfMsg('sdsvideometadata-vc-finished-flag')?>
			<input type="checkbox" name="vcCompleted" id="vcCompleted">
		</label>
		<input type="submit" value="<?= wfMsg('sdsvideometadata-save')?>">
	</form>
<?php } else { ?>
	<?= wfMsg('sdsvideometadata-error-no-video-file')?>
<?php } ?>
