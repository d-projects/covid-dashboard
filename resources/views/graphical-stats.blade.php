@extends('layouts.layout')

@section('page_title')
    Covid-19 Data for {{$province_data['province_name']}}
@endsection
    
@section('content')
<main class = "container">
    <div class = "row">
        <div class = "col-5">
        </div>
        <div class = "col-3">
            <button type = "button" id = "line" class = "btn btn-info"> Cumulative </button>
            <button type = "button" id = "bar" class = "btn btn-info"> Daily </button>
        </div>
        <div class = "col-4">
        </div>
    </div>

    <div class = "charts">
        <canvas id="myChart" width="400" height="225"></canvas>;
        
    </div>
    <br> <br>
</main>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        var dates = @json($province_data['dates']);
        var cases = @json($province_data['cases']);
        var deaths = @json($province_data['deaths']);
    </script>
    <script src = "{{ URL::asset('js/graphs.js')}}"></script>
@endsection


