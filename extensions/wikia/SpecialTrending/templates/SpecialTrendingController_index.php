<h2>Trending</h2>
<table style="color: green">
	<thead>
		<tr>
			<td>Rank</td>
			<td>Title</td>
			<td>Pageviews change</td>
			<td>Pageviews</td>
		</tr>
	</thead>
	<tbody>
		<? for($i=0; $i < count($trendingArticles); $i++): ?>
		<tr>
			<td><?=$i+1?>.</td>
			<td><a href="<?=$trendingArticles[$i]['url'] ?>"><?=$trendingArticles[$i]['title'] ?></a></td>
			<td><?=$trendingArticles[$i]['pvDiff'] ?></td>
			<td><?=$trendingArticles[$i]['pageviews'] ?></td>
		</tr>
		<? endfor ?>
	</tbody>
</table>


<h2>Biggest Loosers</h2>
<table>
	<thead>
	<tr>
		<td>Rank</td>
		<td>Title</td>
		<td>Pageviews change</td>
		<td>Pageviews</td>
	</tr>
	</thead>
	<tbody>
	<? for($i=0; $i < count($loosingArticles); $i++): ?>
		<tr>
			<td><?=$i+1?>.</td>
			<td><a href="<?=$loosingArticles[$i]['url'] ?>"><?=$loosingArticles[$i]['title'] ?></a></td>
			<td><?=$loosingArticles[$i]['pvDiff'] ?></td>
			<td><?=$loosingArticles[$i]['pageviews'] ?></td>
		</tr>
	<? endfor ?>
	</tbody>
</table>
