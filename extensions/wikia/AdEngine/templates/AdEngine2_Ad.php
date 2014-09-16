<?php if ($showAd): ?>
<!-- BEGIN SLOTNAME: <?= htmlspecialchars($slotName) ?> -->
<div id="<?= htmlspecialchars($slotName) ?>" class="wikia-ad noprint default-height">
<script>
	window.adslots2.push(<?= json_encode([$slotName, null, 'AdEngine2']) ?>);
</script>
<?php if (isset($pageFairId)): ?>
<div id="<?= htmlspecialchars($pageFairId) ?>" class="pagefair-acceptable"></div>
<?php endif; ?>
</div>
<!-- END SLOTNAME: <?= htmlspecialchars($slotName) ?> -->
<?php else: ?>
<!-- NO AD <?= htmlspecialchars($slotName) ?> (levels: <?= htmlspecialchars(json_encode($pageTypes)) ?>) -->
<?php endif; ?>
