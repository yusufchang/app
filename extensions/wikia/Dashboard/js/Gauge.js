/**
 * Created by nikodem on 11.12.15.
 */

//http://bl.ocks.org/tomerd/1499279

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

function initialize()
{
    createGauges();
    setTimeout(updateGauges, 3000);

}