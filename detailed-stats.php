
<?
$filename = "https://api.covid19api.com/dayone/country/canada/status/confirmed";
$json_confirmed = file_get_contents($filename, false);
$data_confirmed = json_decode($json_confirmed, true);
$province = $_POST['province'];

// shortening the province name to be displayed if the province is multiple words (e.g. British Columbia => BC)
$province_name = $province;
if (strpos($province, ' ')){
    $province_array = explode(' ', $province);
    $province_name = '';
    foreach ($province_array as $word){
        $province_name .= !ctype_lower(substr($word, 0, 1)) ? substr($word, 0, 1) . '.' : '';
    }
}


$filename_deaths = "https://api.covid19api.com/dayone/country/canada/status/deaths";
$json_deaths = file_get_contents($filename_deaths, false);
$data_deaths = json_decode($json_deaths, true);

$dates = array();
$cases = array();
$deaths = array();

foreach ($data_confirmed as $p){
    if ($p["Province"] == $province){
        $cases[] = $p["Cases"];
        $dates[] = date("m-d", strtotime($p["Date"]));
    }
}

foreach ($data_deaths as $p){
    if ($p["Province"] == $province){
        $deaths[] = $p["Cases"];
    }
}

$dates_json = json_encode($dates);
$cases_json = json_encode($cases);
$deaths_json = json_encode($deaths);


$chart_name = "Covid-19 Data for " . $province;

//echo $json;
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <header class = "alert bg-dark fixed-top text-light">

        <div class = "row">
            <div class = "col-4">
            </div>
            <div class = "col-4">
            <h1 class = "text-center"> Covid-19 Data for <?php echo $province_name ?> </h1>
            </div>
            <div class = "col-4">
            <a href = "covid_19.php"><button type = "button" class = "btn-lg btn-light"> Home </button></a>
        </div>
        </div>
    </header>

    <br> <br> <br> <br>

<main class = "container">
    <canvas id="myChart" width="400" height="225"></canvas>
</main>



<script>

var ctx = document.getElementById('myChart').getContext('2d');

var dates = <? echo $dates_json; ?>;
var cases = <? echo $cases_json; ?>;
var deaths = <? echo $deaths_json; ?>;

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: "Total Cases",
            data: cases,
            lineTension: 0,
            fill: false,
            backgroundColor: "blue",
            borderColor: "blue"
        },
        {
            label: "Total Deaths",
            data: deaths,
            lineTension: 0,
            fill: false,
            backgroundColor: "red",
            borderColor: "red"
        }]
    },
    options: {
        title: {
            display: true,
            //text: '<? echo $chart_name ?>'
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                }
            }],
        }
    }
});
</script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
