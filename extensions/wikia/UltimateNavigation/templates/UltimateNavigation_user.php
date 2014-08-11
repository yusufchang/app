<div class="ultinav-user-links">
	<?= implode(" &bullet; ", $userLinks) ?>
	&bullet; and some more....
</div>
<hr />
<div class="tabs">
	<span class="tab" data-target="profile">Profile</span>
	<span class="tab" data-target="stats">Stats</span>
	<span class="tab" data-target="contributions">Contributions</span>
	What else could go here?
</div>
<hr />
<div class="tab-target" data-target-id="profile">
<?= $userProfile ?>
</div>
<div class="tab-target" data-target-id="stats">
	<div>
		<? foreach ($info as $label => $value) { ?>
			<div><?= $label . ": " . $value ?></div>
		<? } ?>
	</div>
</div>
<div class="tab-target" data-target-id="contributions">
	<?= $userContributions ?>
</div>
