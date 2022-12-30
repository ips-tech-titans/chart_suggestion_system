<div id="lineChart" style="width:100%; height:400px;"></div>
<script>
    Highcharts.chart('lineChart', {
        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },
        xAxis: {
            categories: @json($labels),
        },
        series: [{
            name: 'Installation & Developers',
            data: @json($datasets)
        }],
    });
</script>
