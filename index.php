<?php

function country_stats($country){
	$filename = "https://covid-19-data.p.rapidapi.com/country?format=undefined&name=". $country;
	$host = 'covid-19-data.p.rapidapi.com';
	$key = 'bf8bd3eb2emsh2a0163b2e9d234bp1ee4e8jsn9aa9f2a17eab';

	$opts = [
		'http' => array(
			'method' =>  'GET',
			'header' => array(
				'x-rapidapi-host: ' . $host,
				'x-rapidapi-key: '. $key
			)
		)
	];

	$context = stream_context_create($opts);
	$json = file_get_contents($filename, false, $context);
	$data = json_decode($json, true);

	return [
		'confirmed' => $data[0]['confirmed'],
		'recovered' => $data[0]['recovered'],
		'critical' => $data[0]['critical'],
		'deaths' => $data[0]['deaths']	
	];
}

$filename = "https://covid-19-data.p.rapidapi.com/totals?format=undefined&";
$host = 'covid-19-data.p.rapidapi.com';
$key = 'bf8bd3eb2emsh2a0163b2e9d234bp1ee4e8jsn9aa9f2a17eab';

$opts = [
	'http' => array(
		'method' =>  'GET',
		'header' => array(
			'x-rapidapi-host: ' . $host,
			'x-rapidapi-key: '. $key
		)
	)
];

$context = stream_context_create($opts);
$json = file_get_contents($filename, false, $context);
$data = json_decode($json, true);

$confirmed = number_format($data[0]['confirmed']);
$recovered = number_format($data[0]['recovered']);
$critical = number_format($data[0]['critical']);
$deaths = number_format($data[0]['deaths']);

$canada = country_stats("canada");
$usa = country_stats("usa");
$uk =country_stats("uk");


?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Coronavirus Stats </title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
	<div class = "jumbotron jumbotron-fluid">

		<h1 class = "text-center"> Live Coronavirus Statistics </h1>
	</div>
	<div class = "container">
		<div class = "row">
			<div class = "col">
				<section class = "alert alert-info">
					<h2> Confirmed: <?php echo $confirmed ?> </h2>
				</section>
			</div>

			<div class = "col">
				<section class = "alert alert-success">
					<h2> Recovered: <?php echo $recovered ?> </h2>
				</section>
			</div>
		</div>

		<div class = "row">
			<div class = "col">
				<section class = "alert alert-warning">
					<h2> Critical Condition: <?php echo $critical ?> </h2>
				</section>
			</div>

			<div class = "col">
				<section class = "alert alert-danger">
					<h2> Deaths: <?php echo $deaths ?> </h2>
				</section>
			</div>
		</div>

		<div class = "row">
		
			<div class = "col">
				<section class = "card">
					<section class = "card-body">
						<h4 class = "text-center"> USA Stats </h4>
						<h5> Confirmed: <?php echo $usa['confirmed'] ?> </h5>
						<h5> Deaths: <?php echo $usa['deaths'] ?> </h5>
						<h5> Recovered: <?php echo $usa['recovered'] ?> </h5>
					</section>
				</section>
			</div>

			<div class = "col">
				<section class = "card">
					<section class = "card-body">
						<h4 class = "text-center"> Canada Stats </h4>
						<h5> Confirmed: <?php echo $canada['confirmed'] ?> </h5>
						<h5> Deaths: <?php echo $canada['deaths'] ?> </h5>
						<h5> Recovered: <?php echo $canada['recovered'] ?> </h5>
					</section>
				</section>
			</div>

			<div class = "col">
				<section class = "card">
					<section class = "card-body">
						<h4 class = "text-center"> UK Stats </h4>
						<h5> Confirmed: <?php echo $uk['confirmed'] ?> </h5>
						<h5> Deaths: <?php echo $uk['deaths'] ?> </h5>
						<h5> Recovered: <?php echo $uk['recovered'] ?> </h5>
					</section>
				</section>
			</div>

		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>



 

