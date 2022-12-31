@extends('csv.layout')

@section('content')
    <div class="container-fluid">
        @if (count($charts_data) > 0)
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
                                'yAxis' => $item['yAxis'],
                                'seriesName' => $item['seriesName'],
                            ])
                        </div>
                    @endif

                    @if ($item['type'] == 'scatter')
                        <div class="col-sm-12 col-md-6">
                            @include('high-charts.scatter', [
                                'dataset' => $item['dataset'],
                            ])
                        </div>
                    @endif
                @endforeach



            </div>
        @else
            <h2>We are not able to draw chart for this data. But you can create following charts:</h2>

            <div style="white-space: pre;">
                {!! $openAISuggestion  !!}
            </div>
            
                {{-- @foreach ($openAISuggestion as $suggestion)
                    @if ($suggestion != '')
                        <div>{{ $suggestion }}</div>
                    @endif
                @endforeach             --}}
        @endif
    </div>
@endsection
