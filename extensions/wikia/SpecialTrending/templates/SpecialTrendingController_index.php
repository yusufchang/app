Lorem ipsum
<table>
	<thead>
		<tr>
			<td>Rank</td>
			<td>Title</td>
		</tr>
	</thead>
	<tbody>
		<? for($i=0; $i < count($trendingArticles); $i++): ?>
		<tr>
			<td><?=$i+1?>.</td>
			<td><a href="<?=$trendingArticles[$i]['url'] ?>"><?=$trendingArticles[$i]['title'] ?></a></td>
		</tr>
		<? endfor ?>
	</tbody>
</table>
