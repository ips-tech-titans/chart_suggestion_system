<div id="lineChart" style="width:100%; height:400px;"></div>
<script>
    Highcharts.chart('lineChart', {
        yAxis: {
            title: {
                text: ''
            }
        },
        xAxis: {
            categories: @json($labels),
        },
        series: [{
            name: '',
            data: @json($datasets)
        }],
    });
</script>
