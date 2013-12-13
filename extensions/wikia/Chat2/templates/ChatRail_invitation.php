<span class="chat-invitation">
	<p><?= wfMessage('chat-invitation', [htmlspecialchars($username)]) ?></p>
	<span class="chat-join">
		<button class="accept" data-url="<?= $chatUrl ?>"><?= wfMessage('chat-accept-invitation')->plain() ?></button>
	</span>
	<button class="close wikia-chiclet-button" data-username="<?=htmlentities($username, ENT_QUOTES);?>">
		<img src="<?= $wg->StylePath ?>/oasis/images/icon_close.png">
	</button>
</span>