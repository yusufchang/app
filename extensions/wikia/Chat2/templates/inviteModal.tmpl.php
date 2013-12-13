<laber for="ChatUserToInvite"><?= wfMessage('chat-invite-modal-choose-user') ?></laber>
<select id="ChatUserToInvite">
	<?php foreach( $users as $user ): ?>
		<option value="<?= htmlentities($user, ENT_QUOTES) ?>"><?= htmlspecialchars($user) ?> </option>
	<?php endforeach; ?>
</select>
