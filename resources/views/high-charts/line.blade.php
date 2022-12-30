<div id="lineChart" style="width:100%; height:400px;"></div>
<script>
    Highcharts.chart('lineChart', {
        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },
        xAxis: {
            categories: ["apple", "orange", "mango"],
        },
        series: [{
            name: 'Installation & Developers',
            data: [10, 20, 15]
        }],
    });
</script>
