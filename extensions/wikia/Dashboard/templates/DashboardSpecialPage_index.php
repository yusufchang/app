<!--Poukładaj te kafelki tak, żeby już na starcie fajnie wyglądały, a nie dopiero po pobawieniu się nimi-->
<div class="gridster">
	<ul>
		<li data-row="1" data-col="1" data-sizex="2" data-sizey="2">
			<span class="dashboard-element gauge" id="portabilityGaugeContainer"></span>
		</li>
		<li data-row="2" data-col="1" data-sizex="1" data-sizey="1">
			<p>bla bal bal bla bala some content ;)</p>
		</li>
		<li data-row="3" data-col="1" data-sizex="1" data-sizey="1">
			<p>diananannananananannanan nananan nanann nan<p>
		</li>

		<li data-row="1" data-col="2" data-sizex="4" data-sizey="2">
			<span class="dashboard-element chart" id="templateTypesChart"></span>
		</li>
		<li data-row="2" data-col="2" data-sizex="2" data-sizey="2"></li>

		<li data-row="1" data-col="4" data-sizex="1" data-sizey="1">
			<p>diananannananananannanan nananan nanann nan<p>
		</li>
		<li data-row="2" data-col="4" data-sizex="2" data-sizey="1"></li>
		<li data-row="3" data-col="4" data-sizex="1" data-sizey="1"></li>

		<li data-row="1" data-col="5" data-sizex="1" data-sizey="1">
			<p>diananannananananannanan nananan nanann nan<p>
		</li>
		<li data-row="3" data-col="5" data-sizex="1" data-sizey="1"></li>

		<li data-row="1" data-col="6" data-sizex="1" data-sizey="1">
			<p>diananannananananannanan nananan nanann nan<p>
		</li>
		<li data-row="2" data-col="6" data-sizex="1" data-sizey="2"></li>
	</ul>
</div>

<script src='http://d3js.org/d3.v2.js'></script>

<script>
	var wgStatsData = JSON.parse('<?= json_encode($statsData) ?>');

	initializeGauge();
	InitializeChart();

	function getStatsData(key) {
		return wgStatsData[key];
	}
</script>

<script type="text/javascript">
	require(['jquery','gridster'], function($, gridster) {
		var gridster;
		$(function () {
			gridtster = $(".gridster > ul").gridster({
				widget_margins: [10, 10],
				widget_base_dimensions: [140, 140],
				min_cols: 6
			}).data('gridster');
		});
	})
</script>
