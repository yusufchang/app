<? if ( $exists ) { ?>
<div class="ultinav-user-links">
	<?= implode(" &bullet; ", $articleLinks) ?>
	&bullet; and some more....
</div>
<hr />
<div class="tabs">
	<span class="tab" data-target="contributions">Contributions</span>
	<span class="tab" data-target="preview">Preview</span>
	What else could go here?
</div>
<hr />
<div class="tab-target" data-target-id="contributions">
<div>
	Last edit: <?= $lastEdit ?>
</div>
<div>
	Contributors:
<? if ( $user ) { ?>
<br />&nbsp;&nbsp;<?= UltimateNavigationHelper::formatUser($user) ?>
<? } ?>
<? foreach ($contributors as $contributor) { ?>
<br />&nbsp;&nbsp;<?= UltimateNavigationHelper::formatUser($contributor) ?>
<? } ?>
</div>
</div>
<div class="tab-target" data-target-id="preview">
	<article class="WikiaMainContent">
		<div class="WikiaMainContentContainer">
			<?= $articleContent ?>
		</div>
	</article>
</div>

<? } else { // exists ?>
	Article does not exists.
<? } ?>