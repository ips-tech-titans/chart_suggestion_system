// Create the chart
var options = {
    chart: {
        events: {
            drilldown: function (e) {
                if (!e.seriesOptions) {
                    var chart = this;

                    // Show the loading label
                    chart.showLoading('Loading ...');

                    setTimeout(function () {
                        chart.hideLoading();
                        chart.addSeriesAsDrilldown(e.point, series);
                    }, 1000);
                }

            }
        },
        plotBorderWidth: 0
    },

    title: {
        text: 'Chart Title',
    },
    //
    subtitle: {
        text: 'Subtitle'
    },
    //
    xAxis: {
        type: 'category',
    },
    //
    yAxis: {

        title: {
            margin: 10,
            text: 'No. of user'
        },
    },
    //
    legend: {
        enabled: true,
    },
    //
    plotOptions: {
        series: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: true
            }
        },
        pie: {
            plotBorderWidth: 0,
            allowPointSelect: true,
            cursor: 'pointer',
            size: '100%',
            dataLabels: {
                enabled: true,
                format: '{point.name}: <b>{point.y}</b>'
            }
        }
    },
    exporting: {
        buttons: {
            contextButton: {
                enabled: false
            }
        }
    },
    //
    series: [{
        name: 'Case',
        colorByPoint: true,
        data: [3, 2, 1, 3, 4]
    }],
    //
    drilldown: {
        series: []
    },
    credits: {
        enabled: false
    }
};

// Column chart
options.chart.renderTo = 'container';
options.chart.type = 'column';
var chart1 = new Highcharts.Chart(options);

chartfunc = function () {
    var column = document.getElementById('column');
    var bar = document.getElementById('bar');
    var pie = document.getElementById('pie');
    var line = document.getElementById('line');


    if (column.checked) {

        options.chart.renderTo = 'container';
        options.chart.type = 'column';
        var chart1 = new Highcharts.Chart(options);
    }
    else if (bar.checked) {
        options.chart.renderTo = 'container';
        options.chart.type = 'bar';
        var chart1 = new Highcharts.Chart(options);
    }
    else if (pie.checked) {
        options.chart.renderTo = 'container';
        options.chart.type = 'pie';
        var chart1 = new Highcharts.Chart(options);
    }
    else {
        options.chart.renderTo = 'container';
        options.chart.type = 'line';
        var chart1 = new Highcharts.Chart(options);
    }

}

$('#change_chart_title').click(function () {
    var new_title = $('#chart_title').val();
    var chart = $('#container').highcharts();
    chart.setTitle({ text: new_title });

    alert('Chart title changed to ' + new_title + ' !');

});


function printChart() { chart1.print(); };
function exportPngChart() { chart1.exportChart(); };
function exportJpegChart() {
    chart1.exportChart({
        type: 'image/jpeg'
    });
};
function exportPdfChart() {
    chart1.exportChart({
        type: 'application/pdf'
    });
};
function exportSvgChart() {
    chart1.exportChart({
        type: 'image/svg+xml'
    });
};