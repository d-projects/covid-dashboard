<?php

function single_country_stats($country){
	$filename = "https://covid-19-data.p.rapidapi.com/country?format=undefined&name=". urlencode($country);
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
	foreach ($data[0] as $key => $num){
		$data[0][$key] = number_format(intval($num));
	}

	return [
		'confirmed' => $data[0]['confirmed'],
		'recovered' => $data[0]['recovered'],
		'critical' => $data[0]['critical'],
		'deaths' => $data[0]['deaths']	
	];
}


function countries_display(){
	$filename = "https://api.covid19api.com/summary";
	$json = file_get_contents($filename, false);
	$data = json_decode($json, true);
	$display = '';
	foreach ($data['Countries'] as $c){
		$display .= <<<HTML
			<tr>
				<td> {$c['Country']} </td>
				<td> {$c['TotalConfirmed']} </td>
				<td> {$c['TotalDeaths']} </td>
			</tr>
HTML;
	}
	return $display;
}

$country_display = countries_display();

function province_stats($order){
	$filename = "https://api.apify.com/v2/key-value-stores/fabbocwKrtxSDf96h/records/LATEST?disableRedirect=true";
	$json = file_get_contents($filename, false);
	$data = json_decode($json, true);

	$province_data = [];
	foreach ($order as $province_num){
		$province_data[] = $data['infectedByRegion'][$province_num];
	}
	return $province_data;
}

function province_display($order){
	$display = '';
	for ($p = 0; $p < count($order); $p++){
		$display .= ($p % 2 == 0) ? '<div class = "row">' : '';

		$region = $order[$p]['region'];
		$infected = number_format($order[$p]['infectedCount']);
		$deaths = number_format($order[$p]['deceasedCount']);

		$display .= <<<EOT
			<div class = "col-6">
				<section class = "card alert-secondary">
					<div class = "card-body">
						<div class = "card-text">
							<h4 class = "text-center"> $region </h4>
							<h5> Confirmed: $infected </h5>
							<h5> Deaths: $deaths </h5>
						</div>
					</div>

					<div class = "card-footer">
						<form action = "detailed-stats.php" method = "post">
							<button name = "province" value = "$region"> Detailed Information </button>
						</form>
					</div>
				</section>
			</div>
EOT;
		$display .= ($p % 2 == 1) ? '</div> <br>' : '';
	}
	return $display;
}

// Worldwide stats
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

$canada = single_country_stats("canada");

$country_order = [6, 10, 5, 9, 1, 2, 3, 4, 7, 8, 11, 12, 13];

$province_stats = province_stats($country_order);
$province_display = province_display($province_stats);

?>

<!DOCTYPE html>
<html lang = "en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Coronavirus Stats </title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>

</head>

<body>
	<div class = "jumbotron jumbotron-fluid">

		<h1 class = "text-center"> Live Coronavirus Statistics </h1>
	</div>
	<div class = "container">

			<div class = "row">
				<div class = "col-4">

					<section class = "card text-white bg-dark">
						<div class = "card-body">

							<div class = "card-title text-center">
								<h2> Worldwide </h2>
							</div>

							<div class = "card-text">
								<h4> Confirmed: <?php echo $confirmed ?> </h4>
								<h4> Deaths: <?php echo $deaths ?> </h4>
								<h4> Recovered: <?php echo $recovered ?> </h4>
								<h4> Critical: <?php echo $critical ?> </h4>
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
								<h4> Confirmed: <?php echo $canada['confirmed'] ?> </h4>
								<h4> Deaths: <?php echo $canada['deaths']?> </h4>
								<h4> Recovered: <?php echo $canada['recovered'] ?> </h4>
								<h4> Critical: <?php echo $canada['critical'] ?> </h4>
							</div>

							<div class = "card-text col">
								<h4> New Cases Today: <?php echo 'N/A' ?> </h4>
								<h4> Deaths Today: <?php echo 'N/A' ?> </h4>
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
						<?php echo $country_display; ?>
					</tbody>
				</table>
			</div>

			<div class = "col-8">
				<?php echo $province_display; ?>
			</div>
		</div>

	</div>

	<br> <br>


	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"> </script>

	<script>
		$(document).ready(function() {
		    $('#countryStats').DataTable( {
		        "scrollY":        "400px",
		        "scrollCollapse": true,
		        "paging":         false,
		        "order": [[ 1, "desc" ]]
		    } );
		} );
	</script>

</body>

</html>



 

