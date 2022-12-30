<div id="pieChart" style="width:100%; height:400px;"></div>
<script>
    Highcharts.chart('pieChart', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: @json($data_set)
        }]
    });
</script>
