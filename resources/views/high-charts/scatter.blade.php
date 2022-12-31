<h2>Scatter Chart</h2>
<div id="scatterChart"></div>
<script>
    Highcharts.chart('scatterChart', {
        xAxis: {
            title: {
                text: 'Height'
            }
        },
        yAxis: {
            title: {
                text: 'Weight'
            }
        },
        chart: {
            type: 'scatter'
        },
        series: [{
            data: @json($dataset)
        }],
    });
</script>
