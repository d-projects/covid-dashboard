var ctx = document.getElementById('myChart').getContext('2d');

var charts = document.querySelector('.charts');

function displayLine(){
    
    var ctx = document.getElementById('myChart').getContext('2d');

    myChart = new Chart(ctx, {
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

}

var daily_cases = new Array();
var daily_deaths = new Array();
var last = dates.pop();

for (i = 1; i < cases.length; i++){
    daily_cases.push(cases[i] - cases[i-1]);
    daily_deaths.push(deaths[i] - deaths[i-1]);

}

function displayBar() {

    
    var ctxBar = document.getElementById('myChartBar').getContext('2d');
    myChartBar = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [{
                label: "Daily Cases",
                data: daily_cases,
                fill: false,
                backgroundColor: "blue",
                borderColor: "blue"
            },
            {
                label: "Daily Deaths",
                data: daily_deaths,
                fill: false,
                backgroundColor: "red",
                borderColor: "red"
            }]
        },
        options: {
            title: {
                display: true,
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    });

}

const daily = document.querySelector('#bar');
const total = document.querySelector('#line');

displayLine();

total.addEventListener ("click", function() {
    charts.innerHTML = '<canvas id="myChart" width="400" height="225"></canvas>';
    displayLine();
});
daily.addEventListener ("click", function() {
    charts.innerHTML = '<canvas id="myChartBar" width="400" height="225"></canvas>';
    displayBar();
});