<html>
	<body style="display: none" onload="document.querySelector('form').submit()">
		<form method="POST" action="<?= htmlspecialchars($mercuryUrl); ?>">
			<textarea name="parserOutput"><?= htmlspecialchars($parserOutput) ?></textarea>
			<button type="submit">Go</button>
		</form>
	</body>
</html>
