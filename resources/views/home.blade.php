<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Chart Suggestions System</title>
</head>

<body>
    <header class="header-fixed">
        <div class="header-limiter">
            <h1><a href="#">Chart <span>Suggestions System</span> </a></h1>
        </div>
    </header>
    <section class="database_select">
        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="col-6 col-md-offset-3 col-sm-offset-2">
                        <label>Select Database</label>
                        <select class="js-select2 js-states form-control">
                            <option>School</option>
                            <option>Hospital</option>
                            <option>E-commerce</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-offset-3 col-sm-offset-2">
                        <label>Select Table</label>
                        <select class="js-select2-multi" multiple="multiple">
                            <option>Table 1</option>
                            <option>Table 2</option>
                            <option>Table 3</option>
                            <option>Table 4</option>
                            <option>Table 5</option>
                            <option>Table 6</option>
                            <option>Table 7</option>
                        </select>
                    </div>
                </div>
                <div class="chart_type_select row">
                    <div id="container" class="mt-5" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>
                <div class="export-buttons row">
                    <button class="btn shiny col print">print</button>
                    <button class="btn shiny col" onclick="exportPngChart()">export png</button>
                    <button class="btn shiny col" onclick="exportJpegChart()">export jpeg</button>
                    <button class="btn shiny col" onclick="exportPdfChart()">export pdf</button>
                    <button class="btn shiny col" onclick="exportSvgChart()">export svg</button>
                </div>
            </div>
        </div>


    </section>

</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="js/custom.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('#container').highcharts(<?php
        echo json_encode($chartarray); ?>);

    });
</script>

</html>
