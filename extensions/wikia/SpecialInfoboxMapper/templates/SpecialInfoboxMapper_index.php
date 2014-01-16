<form method="post">

	<table>
		<? foreach( $data as $i => $d ) : ?>
			<tr>
				<td><?= $d['info_key'] ?></td>
				<td><?= $d['template'] ?></td>
				<div>
					<!--		--><?//= $d['samples'] ?>
				</div>
				<td>
					<label for="type_id">Schema type</label>
					<input id="type_id" type="text" name="type_<?= $i ?>" value="<?= $d['type'] ?>" />
				</td>
				<input type="hidden" name="key_<?= $i ?>" value="<?= $d['info_key'] ?>"/>
				<input type="hidden" name="template_<?= $i ?>" value="<?= $d['template'] ?>"/>
				<input type="hidden" name="k" value="<?= $k ?>"/>
			</tr>
		<? endforeach ?>
	</table>

	<button type="submit">Save</button>
</form>

<a href="/Special:InfoboxMapper?k=<?= $k-1 ?>">Prev</a>
<a href="/Special:InfoboxMapper?k=<?= $k+1 ?>">Next</a>
