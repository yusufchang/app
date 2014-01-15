<form method="post">

<? foreach( $data as $i => $d ) : ?>
	<h3><?= $d['key'] ?></h3>
	<h3><?= $d['template'] ?></h3>
	<div>
<!--		--><?//= $d['samples'] ?>
	</div>
	<label for="type_id">Schema type</label>
	<input id="type_id" type="text" name="type_<?= $i ?>" />
	<input type="hidden" name="key_<?= $i ?>" value="<?= $d['key'] ?>"/>
	<input type="hidden" name="k" value="<?= $k ?>"/>
<? endforeach ?>

	<button type="submit">Save</button>
</form>
