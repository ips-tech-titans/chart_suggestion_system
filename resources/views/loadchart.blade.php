<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <title>Chart suggestions</title>
</head>

<body>
    <header class="header-fixed">
        <div class="header-limiter">
            <h1><a href="#">Chart <span>Suggestions</span></a></h1>
        </div>
    </header>
    <section class="database_select">
        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="col-6 col-md-offset-3 col-sm-offset-2">
                        <label>Select Database</label>
                        <select class="js-select2 js-states form-control" id="selected_database">
                            @if(isset($databases))
                            @foreach($databases as $database)
                            <option value="{{$database->Database}}">{{$database->Database}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-6 col-md-offset-3 col-sm-offset-2">
                        <label>Select Table</label>
                        <select class="js-select2-multi" multiple="multiple" id="tablenames">
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
                    <button class="btn shiny col" onclick="printChart()">print</button>
                    <button class="btn shiny col" onclick="exportPngChart()">export png</button>
                    <button class="btn shiny col" onclick="exportJpegChart()">export jpeg</button>
                    <button class="btn shiny col" onclick="exportPdfChart()">export pdf</button>
                    <button class="btn shiny col" onclick="exportSvgChart()">export svg</button>
                </div>
            </div>
        </div>
    </section>
 
</body>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="{{asset('js/chartcustom.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>

<script>
    $( document ).ready(function() {
        getDataFromSelectedDB();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $("#selected_database").on("change", function(){
            let database = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{route('setdatabase')}}",
                dataType: 'JSON',
                type: "GET",
                data:{'_token': CSRF_TOKEN, 'database':database},
                success: function (response) {
                    getDataFromSelectedDB();
                }
            });
        });

        $("#tablenames").on("change", function(){
            let database = $("#selected_database").val();
            let tables = $(this).val();
            console.log(tables);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{route('getDataFromSelectedTableswithDb')}}",
                dataType: 'JSON',
                type: "POST",
                data:{'_token': CSRF_TOKEN, 'database':database, 'tables':tables},
                success: function (response) {
                    $('#container').highcharts(response.data)

                }
            });
        });
    });

    function getDataFromSelectedDB(){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{route('getalltables')}}",
            dataType: 'JSON',
            type: "GET",
            success: function (response) {
                $("#tablenames").html('');
                console.log(response);
                if(response.success){
                    var html = "";
                    $.each(response.data, function (key, val) {
                        html +="<option>"+val+"</option>";
                        $("#tablenames").append($('<option>', {value:val, text: val}));
                    });
                } else {

                }
                
            }
        });
    }
</script>
</html>