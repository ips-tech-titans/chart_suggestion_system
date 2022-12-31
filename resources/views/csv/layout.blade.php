<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('') }}css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('') }}css/styles.css">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <title>Chart Suggestions System</title>
</head>

<body>

    <header class="header-fixed">
        <div class="header-limiter">
            <h1><a href="#">Chart <span>Suggestions System</span> </a></h1>
        </div>
    </header>
    

    @yield('content')
    
    <script src="{{ asset('') }}js/jquery.min.js"></script>
    <script src="{{ asset('') }}js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}js/popper.min.js"></script>
    <script src="{{ asset('') }}js/bootstrap.min.js"></script>
</body>

</html>
