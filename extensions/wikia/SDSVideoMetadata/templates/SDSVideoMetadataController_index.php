<?php if ( $isCorrectFile ) { ?>
	<h1>Matadata for: <?= $file ?></h1>
	<form class="WikiaForm VMDForm" method="POST">
		<fieldset>
			<legend>Common video clip properties:</legend>
			<div class="input-group">
				<label for="vcTitle">Title</label>
				<input type="text" name="schema:name" id="vcTitle">
			</div>
			<div class="input-group">
				<label for="vcDescription">Description</label>
				<textarea name="schema:description" id="vcDescription"></textarea>
			</div>
			<div class="input-group">
				<label for="vcPublishedDate">Published date</label>
				<input type="text" name="schema:datePublished" id="vcPublishedDate">
			</div>
			<div class="input-group">
				<label for="vcLanguage">Language</label>
				<input type="text" name="schema:inLanguage" id="vcLanguage">
			</div>
			<div class="input-group">
				<label for="vcSubtitles">Subtitles</label>
				<input type="text" name="schema:subTitleLanguage" id="vcSubtitles">
			</div>
		</fieldset>
		<fieldset>
			<legend>Type specific video clip properties:</legend>

			<input type="hidden" name="vcType" value="gamingVideos">

			<div class="input-group gamingVideos">
				<label for="vcGame">Game</label>
				<input type="text" name="schema:name[]" id="vcGame">
			</div>
			<button class="secondary">Add more</button>
			<div class="input-group gamingVideos">
				<label for="vcSpecType">Type</label>
				<input type="text" name="?????" id="vcSpecType">
			</div>
			<button class="secondary">Add more</button>
			<div class="input-group gamingVideos">
				<label for="vcAgregate">Agregate</label>
				<select name="schema:isFamilyFriendly" id="vcAgregate">
					<option>not set</option>
					<option value="true">Yes</option>
					<option value="false">No</option>
				</select>
			</div>
			<div class="input-group gamingVideos">
				<label for="vcSoundtrack">Soundtrack</label>
				<input type="text" name="schema:associatedMedia[]" id="vcSoundtrack">
			</div>
			<button class="secondary">Add more</button>
			<div class="input-group gamingVideos">
				<label for="vcSetting">Setting</label>
				<input type="text" name="wikia:setting[]" id="vcSetting">
			</div>
			<button class="secondary">Add more</button>
		</fieldset>
		<input type="submit" value="Save">
	</form>
<?php } else { ?>
	THERE IS NO VIDEO FILE
<?php } ?>
