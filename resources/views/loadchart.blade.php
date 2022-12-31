<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Chart suggestions</title>
    <style>
        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 120px;
            height: 120px;
            margin: -76px 0 0 -76px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            display: none;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0;
                opacity: 1
            }
        }
    </style>
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
                            @if (isset($databases))
                                @foreach ($databases as $database)
                                    <option value="{{ $database->Database }}">{{ $database->Database }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-6 col-md-offset-3 col-sm-offset-2">
                        <label>Select Table</label>
                        <select class="js-select2-multi" id="tablenames">
                            <!-- multiple="multiple" -->
                        </select>
                    </div>
                </div>
                <div id="chart_messages_from_ai"></div>
                <div class="chart_type_select row">
                    <div id="container" class="mt-5" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>

            </div>
        </div>
    </section>

    <div id="loader"></div>


</body>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<!-- <script src="{{ asset('js/chartcustom.js') }}"></script> -->
<script src="{{ asset('js/custom.js') }}"></script>

<script>
    $(document).ready(function() {
        getDataFromSelectedDB();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $("#selected_database").on("change", function() {
            let database = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('setdatabase') }}",
                dataType: 'JSON',
                type: "GET",
                data: {
                    '_token': CSRF_TOKEN,
                    'database': database
                },
                success: function(response) {
                    getDataFromSelectedDB();
                }
            });
        });

        $("#tablenames").on("change", function() {
            $("#loader").show();
            let database = $("#selected_database").val();
            let tables = $(this).val();
            console.log(tables);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('getDataFromSelectedTableswithDb') }}",
                dataType: 'JSON',
                type: "POST",
                data: {
                    '_token': CSRF_TOKEN,
                    'database': database,
                    'tables': tables
                },
                success: function(response) {                    
                    $("#container").highcharts(response.chart_suggestion[0]);
                    console.log(response);
                    if (response.messages.length > 0) {
                        $("#chart_messages_from_ai").html(response.messages[0]);
                    }
                },
                complete: function() {
                    $("#loader").hide();
                }
            });
        });
    });

    function getDataFromSelectedDB() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: "{{ route('getalltables') }}",
            dataType: 'JSON',
            type: "GET",
            success: function(response) {
                $("#tablenames").html('');
                $("#tablenames").append($('<option>', {
                    value: '',
                    text: ''
                }));
                console.log(response);
                if (response.success) {
                    var html = "";
                    if (response.data.length > 0) {
                        $.each(response.data, function(key, val) {
                            html += "<option>" + val + "</option>";
                            $("#tablenames").append($('<option>', {
                                value: val,
                                text: val
                            }));
                        });
                    }
                } else {

                }

            }
        });
    }
</script>

</html>
