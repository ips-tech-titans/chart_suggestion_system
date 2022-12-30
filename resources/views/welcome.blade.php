<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>

<body>
    <select id="dropdown">
      
    </select>


    <div id="container" style="min-width: 310px; height: 600px; margin: 0 auto"></div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#container').highcharts(<?php
               echo json_encode($chartarray);?>)
        });
        $("#dropdown").change(function() {
            var selectedVal = $('#dropdown :selected').text();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/postdata",
                data: {
                    'selectedVal': selectedVal
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#dropnew').remove()
                    console.log(data.dropdown);
                    var html = '';
                    html += "<option>please select any value</option>"
                    $.each(data.dropdown, function(index, option) {
                        html += "<option value=" + option + ">" + option + "</option>";
                    });
                    $('body').prepend('<select id="dropnew">' +
                        html + '</select>');

                }
            });
        });
        $(document).on('change', '#dropnew', function() {

            var selectedVal = $('#dropnew :selected').text();
            var selectedVal2 = $('#dropdown :selected').text();
            console.log(selectedVal)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/postdata/new",
                data: {
                    'selectedVal': selectedVal,
                    'selectedVal2': selectedVal2
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data)
                    $('#container').data('')
                    $('#container').highcharts(data.chartArray)

                    var html = '';
                    html += "<option>please select any value</option>"
                    $.each(data.dropdown, function(index, option) {
                        html += "<option value=" + option + ">" + option + "</option>";
                    });
                    $('body').prepend('<select id="dropnew2">' +
                        html + '</select>');

                }
            });
        });

        $(document).on('change', '#dropnew2', function() {

            var selectedVal = $('#dropnew :selected').text();
            var selectedVal2 = $('#dropdown :selected').text();
            var selectedVal3 = $('#dropnew2 :selected').text();

            console.log(selectedVal)
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/postdata/multi",
                data: {
                    'selectedVal': selectedVal,
                    'table_name': selectedVal2,
                    'selectedVal3':selectedVal3
                },
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data)
                    $('#container').data('')
                    $('#container').highcharts(data.chartArray)

                    var html = '';
                    html += "<option>please select any value</option>"
                    $.each(data.dropdown, function(index, option) {
                        html += "<option value=" + option + ">" + option + "</option>";
                    });
                    $('body').prepend('<select id="dropnew2">' +
                        html + '</select>');

                }
            });
        });
    </script>
</body>

</html>