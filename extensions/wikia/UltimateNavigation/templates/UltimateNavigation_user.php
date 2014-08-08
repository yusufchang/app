<?= $userProfile ?>
<hr />
<div class="ultinav-user-links">
<?= implode(" &bullet; ", $userLinks) ?>
</div>
<hr />
<div>
<? foreach ($info as $label => $value) { ?>
<div><?= $label . ": " . $value ?></div>
<? } ?>
</div>