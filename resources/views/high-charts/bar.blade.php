<h2>Bar Chart</h2>
<div id="barChart"></div>
<script>
    Highcharts.chart('barChart', {
        chart: {
            type: 'column'
        },
        xAxis: {
            categories: @json($labels),
            crosshair: true
        },
        series: [{
            name: '',
            data: @json($datasets)

        }]
    });
</script>
