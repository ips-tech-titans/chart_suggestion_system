@extends('csv.layout')

@section('content')
    <div class="container-fluid">
        <div class="row">

            @foreach ($charts_data as $item)
                @if ($item['type'] == 'bar')
                    <div class="col-sm-12 col-md-6">
                        @include('high-charts.bar', [
                            'labels' => $item['labels'],
                            'datasets' => $item['data_set'],
                        ])
                    </div>
                @endif

                @if ($item['type'] == 'pie')
                    <div class="col-sm-12 col-md-6">
                        @include('high-charts.pie', [
                            'data_set' => $item['data_set'],
                        ])
                    </div>
                @endif

                @if ($item['type'] == 'line')
                    <div class="col-sm-12 col-md-6">
                        @include('high-charts.line', [
                            'labels' => $item['labels'],
                            'datasets' => $item['data_set'],
                        ])
                    </div>
                @endif
            @endforeach
            {{-- <div class="col-sm-12 col-md-6">
                @include('high-charts.pie')
            </div>
            <div class="col-sm-12 col-md-6">
                @include('high-charts.line')
            </div> --}}
        </div>
    </div>
@endsection
