@extends('layouts.app')

@section('title', 'Station information')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.js" integrity="sha256-1qeNeAAFNi/g6PFChfXQfa6CQ8eXoHXreohinZsoJOQ=" crossorigin="anonymous"></script>
    <style>
        .container {
            height: 400px;
            width: 1300px;
        }
        #myChart {
            width: 500px;
            height: 500px;
        }
    </style>
@endsection

@section('content')

<div id="demo"></div>
<canvas id="myChart" width="400" height="400"></canvas>

<table>
    <tr>
        <th>Temperature</th>
        <th id="temperature">null</th>
    </tr>
    <tr>
        <th>Visibility</th>
        <th id="visibility">null</th>
    </tr>
</table>




<script>
    var points = {!! $data !!};
    var ctx = document.getElementById("myChart");
    var temperature = document.getElementById('temperature');
    var visibility = document.getElementById('visibility');


    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: data = {
            labels: {!! $xas !!},
            datasets: [
                {
                    label: "humidity",
                    backgroundColor: "rgba(75,192,192,0.4)",
                    data: points,

                }
            ]
        },

        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        max: 100,
                        min: 0,
                        stepSize: 5
                    }
                }]
            }
        }
    });

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    async function demo() {
        console.log('Taking a break...');
        await sleep(2000);
        console.log('Two second later');
    }

    //myLineChart.data.datasets[0].data[2] = 10;
    //myLineChart.update();
    function loadData() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function (table) {
            if (this.readyState == 4 && this.status == 200) {
                var myObj = JSON.parse(this.responseText);
                myLineChart.data.datasets[0].data = myObj.data;
                myLineChart.update();
                temperature.innerHTML = myObj.temperature;
                visibility.innerHTML = myObj.visibility;
            }
        };
        xhttp.open("GET", "/ajax/{{ $stn }}", true);
        xhttp.send();
    }

    setInterval(function(){
        loadData(); // this will run after every 5 seconds
    }, 5000);

</script>

@endsection