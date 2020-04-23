<?php

date_default_timezone_set('US/Pacific');

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
		$country = $c['Country'];
		$confirmed = number_format($c['TotalConfirmed']);
		$deaths = number_format($c['TotalDeaths']);
		$display .= <<<HTML
			<tr>
				<td> $country </td>
				<td> $confirmed </td>
				<td> $deaths </td>
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

	$d = date('Y-m-d', time() - 60*60*24);
	/*$yesterday = 'https://api.covid19api.com/country/canada/status/confirmed/live?from=' . $d . 'T00:00:00Z&to=' . $d . 'T00:00:01Z';
	$json1 = file_get_contents($yesterday, false);
	$live1 = json_decode($json1, true);*/

	$yesterday2 = 'https://api.covid19api.com/country/canada/status/deaths/live?from=' . $d . 'T00:00:00Z&to=' . $d . 'T00:00:01Z';
	$json2 = file_get_contents($yesterday2, false);
	$live2 = json_decode($json2, true);

	$test_file = 'https://api.covid19api.com/dayone/country/canada';
	$test_json = file_get_contents($test_file, false);
	$test_live = json_decode($test_json, true);

	$confirmed_array = array();
	$confirmed_array_old = array();

	$death_array = array();
	$death_array_old = array();

	$date_array = array();

	$count = 1;
	$provinces = 0;
	$test_size = sizeof($test_live);
	while ($provinces < 26){

		$index = $test_live[$test_size - $count]['Province'];
		if (!isset($confirmed_array[$index])){
			$confirmed_array[$index] = $test_live[$test_size - $count]['Confirmed'];
			$death_array[$index] = $test_live[$test_size - $count]['Deaths'];
			$date_array[$index] = $test_live[$test_size - $count]['Date'];
			$provinces++;
		}
		elseif (!isset($confirmed_array_old[$index])){
			$confirmed_array_old[$index] = $test_live[$test_size - $count]['Confirmed'];
			$death_array_old[$index] = $test_live[$test_size - $count]['Deaths'];
			$provinces++;
		}

		$count++;
	}

	$provinces_confirmed = array();
	$provinces_deaths = array();

	foreach ($confirmed_array as $province => $c) {
		$provinces_confirmed[$province] = $c - $confirmed_array_old[$province];
		$provinces_deaths[$province] = $death_array[$province] - $death_array_old[$province];		
	}




	$province_data = array();
	foreach ($order as $province_num => $province_name){
		/*$today_cases = $data['infectedByRegion'][$province_num]['infectedCount'] - $live1[$live_stats1[$province_name]]['Cases'];
		$data['infectedByRegion'][$province_num]['today_cases'] = ($today_cases > 0) ? $today_cases : 'Not Updated';*/
		$test['confirmed_today'] = $provinces_confirmed[$province_name];
		$test['confirmed'] = $confirmed_array[$province_name];
		$test['region'] = $province_name;

		$test['deaths_today'] = $provinces_deaths[$province_name];
		$test['deaths'] = $death_array[$province_name];
		$test['date'] = $date_array[$province_name];


		$province_data[] = $test;
	}
	return $province_data;
}

function province_display($order){
	$display = '';

	for ($p = 0; $p < count($order); $p++){
		$display .= ($p % 2 == 0) ? '<div class = "row">' : '';

		$region = $order[$p]['region'];
		$infected = number_format($order[$p]['confirmed']);
		$deaths = number_format($order[$p]['deaths']);
		$today_cases = number_format($order[$p]['confirmed_today']);
		$today_deaths = number_format($order[$p]['deaths_today']);
		$date = date('F d', strtotime($order[$p]['date']) + 60*60*24);

		$display .= <<<EOT
			<div class = "col-6">
				<section class = "card alert-secondary">
					<div class = "card-body">
						<div class = "card-text">
							<h4 class = "text-center"> $region </h4>
							<h6> $date Confirmed: $today_cases </h6>
							<h6> $date Deaths: $today_deaths </h6>
							<h6> Total Confirmed: $infected </h6>
							<h6> Total Deaths: $deaths </h6>
							
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

$country_order = [
	6 => 'Ontario',
	10 => 'British Columbia',
	5 => 'Quebec',
	9 => 'Alberta',
	2 => 'Prince Edward Island',
	3 => 'Nova Scotia',
	4 => 'Manitoba',
	7 => 'Saskatchewan',
	8 => 'New Brunswick',
	11 => 'Yukon',
	12 => 'Northwest Territories',
	13 => 'Nunavut',
	1 => 'Newfoundland and Labrador'
];

$province_stats = province_stats($country_order);
$province_display = province_display($province_stats);

$canada_today_deaths = 0;
$canada_today_confirmed = 0;

foreach ($province_stats as $p){
	$canada_today_deaths += $p['deaths_today'];
	$canada_today_confirmed += $p['confirmed_today'];
}

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
	<header class = "fixed-top alert bg-dark text-light">

			<h1 class = "text-center"> Live Covid-19 Statistics </h1>

	</header>

	<br> <br> <br> <br> <br> <br>


		<main class = "container">

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
									<h4> New Cases Today: <?php echo $canada_today_confirmed ?>* </h4>
									<h4> Deaths Today: <?php echo $canada_today_deaths ?>* </h4>
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

		</main>

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



 

