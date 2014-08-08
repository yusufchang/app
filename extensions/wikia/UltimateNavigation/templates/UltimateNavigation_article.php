<? if ( $exists ) { ?>
<div style="float:left; width: 200px; max-height: 500px; overflow-y: auto">
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

<div style="float:right; width: 580px; overflow-y: scroll; max-height: 500px">
	<?= $articleContent ?>
</div>

<? } else { // exists ?>
	Article does not exists.
<? } ?>