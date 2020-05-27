@extends('layouts.layout')

@section('page_title', 'Live Covid-19 Statistics')
	
@section('content')
		<main class = "container">

				<div class = "row">
					<div class = "col-4">

						<section class = "card text-white bg-dark">
							<div class = "card-body">

								<div class = "card-title text-center">
									<h2> Worldwide </h2>
								</div>

								<div class = "card-text">
									<h4> Confirmed: {{ $data['worldwide']['confirmed'] }} </h4>
									<h4> Deaths: {{ $data['worldwide']['deaths'] }} </h4>
									<h4> Recovered: {{ $data['worldwide']['recovered'] }} </h4>
									<h4> Critical: {{ $data['worldwide']['critical'] }} </h4>
								</div>
						
							</div>
						</section>
					</div>

					<div class = "col-8">

						<section class = "card alert-secondary">
							<div class = "card-body">

								<div class = "card-title text-center">
									<h2> Canada </h2>
								</div>

								<div class = "row">
								<div class = "card-text col">
									<h4> Confirmed: {{ $data['canada']['confirmed'] }} </h4>
									<h4> Deaths: {{ $data['canada']['deaths'] }} </h4>
									<h4> Recovered: {{ $data['canada']['recovered'] }} </h4>
									<h4> Critical: {{ $data['canada']['critical'] }} </h4>
								</div>

								<div class = "card-text col">
									<h4> New Cases Today: {{$data['canada']['new_confirmed']}} </h4>
									<h4> Deaths Today: {{$data['canada']['new_deaths']}} </h4>
								</div>
							</div>
						
							</div>
						</section>
					</div>
				</div>

				<br>

			<div class = "row">
				<div class = "col-4">
					<table id = "countryStats" class = "table table-striped table-bordered table-hover">
						<thead class = "thead-dark">
							<tr>
								<th> Country </th>
								<th> Confirmed </th>
								<th> Deaths </th>
							</tr>
						</thead>

						<tbody>

							@foreach ($data['country_stats']['Countries'] as $c)
                                <tr>
                                    <td> {{ $c['Country'] }} </td>
                                    <td> {{ $c['TotalConfirmed'] }} </td>
                                    <td> {{ $c['TotalDeaths'] }} </td>
                                </tr>
                            @endforeach

						</tbody>
					</table>
				</div>

				<div class = "col-8">
					@foreach ($data['display'] as $p => $info)
						@if ($info['number'] % 2 == 0)
							<div class = "row">
						@endif
						
						<div class = "col-6">
							<section class = "card alert-secondary">
								<div class = "card-body">
									<div class = "card-text">
										<h4 class = "text-center"> {{$p}} </h4>
										@if (isset($info))
											<h5> Total Confirmed: {{$info['confirmed']}} </h5>
											<h5> Total Deaths: {{$info['deaths']}} </h5>
										@endif
										
									</div>
								</div>

								<div class = "card-footer">
									
										<button type = "submit" class = "btn btn-info"><a class = "province-link" href = "province/{{$p}}"> Graphical Information </a> </button>
									
								</div>
							</section>
						</div>
						
						@if ($info['number'] % 2 == 1)
						</div> <br>
						@endif

					@endforeach
				</div>
			</div>

		</main>

	<br> <br>

@endsection

@section('scripts')
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>

	<script>
		$(document).ready(function() {
			$('#countryStats').DataTable( {
				"scrollY": "400px",
				"scrollCollapse": true,
				"paging": false,
				"order": [[ 1, "desc" ]]
			} );
		} );
	</script>
@endsection



 

