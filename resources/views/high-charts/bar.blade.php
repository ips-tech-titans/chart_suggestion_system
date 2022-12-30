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
            name: 'Tokyo',
            data: @json($datasets)

        }]
    });
</script>
