<script src='http://d3js.org/d3.v2.js'></script>
<script src='http://d3js.org/d3.v3.min.js'></script>


<!--Poukładaj te kafelki tak, żeby już na starcie fajnie wyglądały, a nie dopiero po pobawieniu się nimi-->
<div class="gridster">
	<ul>
		<li data-row="1" data-col="1" data-sizex="2" data-sizey="2">
			<p>Portability:</p>
			<svg id="gaugePortability" width="19%" height="200"></svg>
		</li>

		<li data-row="1" data-col="3" data-sizex="2" data-sizey="2">
			<span class="dashboard-element gauge" id="portabilityGaugeContainer"></span>
		</li>

		<li data-row="3" data-col="1" data-sizex="2" data-sizey="2">
			<p>WAM Score:</p>
			<svg id="gaugeWAM" width="19%" height="200" ></svg>
		</li>

		<li data-row="5" data-col="1" data-sizex="4" data-sizey="3">
			<p>Template types distribution:</p>
			<span class="dashboard-element chart" id="templateTypesChart"></span>
		</li>

		<li data-row="4" data-col="3" data-sizex="4" data-sizey="4">
			<p>Most active users:</p>
			<div id="mostActiveUsers"></div>
		</li>
	</ul>
</div>

<script>
	var wgStatsData = JSON.parse('<?= json_encode($statsData) ?>');

	initializeGauge();
	InitializeChart();

	function getStatsData(key) {
		return wgStatsData[key];
	}
</script>

<script type="text/javascript">

	/* liquidGauge */
	require(['jquery','gridster'], function($, gridster) {
		var gridster;
		$(function () {
			gridtster = $(".gridster > ul").gridster({
				widget_margins: [10, 10],
				widget_base_dimensions: [140, 140],
				min_cols: 6
			}).data('gridster');
		});
	});

	var gaugeWAMconfig = liquidFillGaugeDefaultSettings();
	gaugeWAMconfig.textVertPosition = 0.5;
	gaugeWAMconfig.waveAnimateTime = 2000;
	gaugeWAMconfig.waveCount = 5;
	gaugeWAMconfig.waveHeight = 0.5;
	gaugeWAMconfig.waveOffset = 0.25;
	gaugeWAMconfig.valueCountUp = false;
	gaugeWAMconfig.displayPercent = false;
	var gaugeWAM = loadLiquidFillGauge("gaugeWAM", getStatsData('WAMScore'), gaugeWAMconfig);
	var gaugePortabilityconfig = liquidFillGaugeDefaultSettings();
	gaugePortabilityconfig.circleThickness = 0.15;
	gaugePortabilityconfig.circleColor = "#808015";
	gaugePortabilityconfig.textColor = "#555500";
	gaugePortabilityconfig.waveTextColor = "#FFFFAA";
	gaugePortabilityconfig.waveColor = "#AAAA39";
	gaugePortabilityconfig.textVertPosition = 0.7;
	gaugePortabilityconfig.waveAnimateTime = 1000;
	gaugePortabilityconfig.waveHeight = 0.05;
	gaugePortabilityconfig.waveAnimate = true;
	gaugePortabilityconfig.waveRise = false;
	gaugePortabilityconfig.waveHeightScaling = false;
	gaugePortabilityconfig.waveOffset = 0.25;
	gaugePortabilityconfig.textSize = 0.75;
	gaugePortabilityconfig.waveCount = 3;
	var gaugePortability = loadLiquidFillGauge("gaugePortability", getStatsData('portability'), gaugePortabilityconfig);

	/* users bubble*/
	var diameter = 560,
		format = d3.format(",d"),
		color = d3.scale.category20c();

	var bubble = d3.layout.pack()
		.sort(null)
		.size([diameter, diameter])
		.padding(1.5);

	var svg = d3.select("#mostActiveUsers").append("svg")
		.attr("width", diameter)
		.attr("height", diameter)
		.attr("class", "bubble");

	var topUsersData = {
		"name": "users",
		"children": [
			{"name": "Gta-mysteries", "size": 95},
			{"name": "Smashbro8", "size": 73},
			{"name": "Ilan xd", "size": 68},
			{"name": "LS11sVaultBoy", "size": 54},
			{"name": "The Tom", "size": 49},
			{"name": "Gboyers", "size": 44},
			{"name": "WildBrick142", "size": 42}
		]
	};

	var node = svg.selectAll(".node")
		.data(bubble.nodes(classes(topUsersData))
			.filter(function(d) { return !d.children; }))
		.enter().append("g")
		.attr("class", "node")
		.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

	node.append("title")
		.text(function(d) { return d.className + ": " + format(d.value); });

	node.append("circle")
		.attr("r", function(d) { return d.r; })
		.style("fill", function(d) { return color(d.packageName); });

	node.append("text")
		.attr("dy", ".3em")
		.style("text-anchor", "middle")
		.text(function(d) { return d.className.substring(0, d.r / 3); });

	// Returns a flattened hierarchy containing all leaf nodes under the root.
	function classes(topUsersData) {
		var classes = [];

		function recurse(name, node) {
			if (node.children) {
				node.children.forEach(function(child) {
					recurse(node.name, child);
				});
			} else {
				classes.push({packageName: '' + name + Math.random(), className: node.name, value: node.size});
			}
		}

		recurse(null, topUsersData);
		return {children: classes};
	}

	d3.select(self.frameElement).style("height", diameter + "px");
</script>
