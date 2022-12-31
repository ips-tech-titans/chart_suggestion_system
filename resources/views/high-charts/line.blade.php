<h2>Line Chart</h2>
<div id="lineChart" style="width:100%; height:400px;"></div>
<script>
    Highcharts.chart('lineChart', {
        title: {
            text: '',          
        },
        yAxis: {
            title: {
                text: '{{ $yAxis }}'
            }
        },
        xAxis: {
            categories: @json($labels),
        },
        series: [{
            name: '{{ $seriesName }}',
            data: @json($datasets)
        }],
    });
</script>
