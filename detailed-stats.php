
<?
$filename = "https://api.covid19api.com/dayone/country/canada/status/confirmed";
$json = file_get_contents($filename, false);
$data = json_decode($json, true);
$province = $_POST['province'];
$dates = [];
$cases = [];
foreach ($data as $p){
    if ($p["Province"] == $province){
        $cases[] = $p["Cases"];
        $dates[] = date("m-d", strtotime($p["Date"]));
    }
}

$dates_json = json_encode($dates);
$cases_json = json_encode($cases);


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
<section class = "container">
    <canvas id="myChart" width="400" height="250"></canvas>
</section>

<script>

var ctx = document.getElementById('myChart').getContext('2d');

var dates = <? echo $dates_json; ?>;
var cases = <? echo $cases_json; ?>;

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: "Total Cases",
            data: cases,
            lineTension: 0
        }]
    },
    options: {
        title: {
            display: true,
            text: '<? echo $chart_name ?>'
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
