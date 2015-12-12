<div>
<span class="dashboard-element gauge" id="portabilityGaugeContainer"></span>
<span class="dashboard-element chart" id="templateTypesChart"></span>
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
