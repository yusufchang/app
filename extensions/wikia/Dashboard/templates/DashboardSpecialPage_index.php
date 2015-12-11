<h4>Twoj dashboard:</h4>
<div class="results">
    <div class="element">Portability Twojej wiki to: <?= $portability ?></div>
</div>
<body onload="initialize()">
    <span id="portabilityGaugeContainer"></span>
</body>

<!-- http://bl.ocks.org/tomerd/1499279 -->
<script src="http://d3js.org/d3.v3.min.js"></script>
<script type="text/javascript" src="http://mbostock.github.com/d3/d3.js"></script>

<script type="text/javascript">
    var gauges = [];

    function createGauge(name, label, min, max)
    {
        var config =
        {
            size: 200,
            label: label,
            min: undefined != min ? min : 0,
            max: undefined != max ? max : 100,
            minorTicks: 5
        }

        var range = config.max - config.min;
        config.redZones = [{ from: config.min, to: config.max*0.50 }];
        config.yellowZones = [{ from: config.min + range*0.50, to: config.min + range*0.75 }];
        config.greenZones = [{ from: config.min + range*0.75, to: config.max }];

        gauges[name] = new Gauge(name + "GaugeContainer", config);
        gauges[name].render();
    }

    function createGauges()
    {
        createGauge("portability", "Portability");
    }

    function updateGauges()
    {
        for (var key in gauges)
        {
            var value = getPortabilityValue(gauges[key])
            gauges[key].redraw(value);
        }
    }

    function getPortabilityValue(gauge)
    {
        var portability = "<?php echo $portability; ?>";
        return portability
    }

    function initialize()
    {
        createGauges();
        setTimeout(updateGauges, 3000);

    }
</script>

