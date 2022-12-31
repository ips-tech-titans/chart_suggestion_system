@extends('csv.layout')

@section('content')
    <section class="database_select home">       
        <div class="bg"></div>
        <div class="bg bg2"></div>
        <div class="bg bg3"></div>
        <div class="content">
            <h1>Choose Type</h1>
            <div class="row">
                <div class="col-6 col-md-offset-3 col-sm-offset-2 text-center">
                    <button class="btn shiny col">
                        <a href="{{ route('loadchart') }}" style="text-decoration: none;color:white;">Use SQL</a>
                    </button>
                </div>
                <div class="col-6 col-md-offset-3 col-sm-offset-2 text-center">
                    <button class="btn shiny col">
                        <a href="{{ route('csv-file') }}" style="text-decoration: none;color:white;">Use CSV</a>
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
