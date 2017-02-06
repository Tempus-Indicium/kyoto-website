@extends('layouts.app')

@section('title', 'Station information')

@section('extra-header-ding')
    <style>
        #myChart {
            width: 500px;
            height: 500px;
        }
    </style>
@endsection

@section('content')
<h1>Station page</h1>
<div class="grafiek">
    <canvas id="myChart"></canvas>
</div>


<table class="table">
    <thead>
        <tr>
            <th>
                Country
            </th>
            <td>
                {{ $stn->country }}
            </td>
        </tr>
        <tr>
            <th>
                Name
            </th>
            <td>
                {{ $stn->name }}
            </td>
        </tr>
    </thead>
    <tr>
        <th>Temperature in Celsius</th>
        <td id="temperature">Loading..</td>
    </tr>
    <tr>
        <th>Visibility in km</th>
        <td id="visibility">Loading..</td>
    </tr>
</table>




<script>
    $(document).ready(function() {
        var points = {!! $data !!};
        var ctx = document.getElementById("myChart");
        var temperature = document.getElementById('temperature');
        var visibility = document.getElementById('visibility');

        // Chart.defaults.global = {
        //   animationSteps : 50,
        //   tooltipYPadding : 16,
        //   tooltipCornerRadius : 0,
        //   tooltipTitleFontStyle : 'normal',
        //   tooltipFillColor : 'rgba(0,160,0,0.8)',
        //   animationEasing : 'easeOutBounce',
        //   scaleLineColor : 'black',
        //   scaleFontSize : 16
        // };

        var myLineChart = new Chart(ctx, {
            type: 'line',
            responsive: true,
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

    //    function sleep(ms) {
    //        return new Promise(resolve => setTimeout(resolve, ms));
    //    }
    //    async function demo() {
    //        console.log('Taking a break...');
    //        await sleep(2000);
    //        console.log('Two second later');
    //    }

        // myLineChart.data.datasets[0].data[2] = 10;
        // myLineChart.update();
        var _xPointCounter = {!! count(explode(",", trim($data, "[]"))) !!};
        function loadData() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function (table) {
                if (this.readyState == 4 && this.status == 200) {
                    var myObj = JSON.parse(this.responseText);
                    temperature.innerHTML = myObj.temperature;
                    visibility.innerHTML = myObj.visibility;
                    if (_xPointCounter == 120) {
                        // refresh graph
                        myLineChart.data.datasets[0].data = [];
                        myLineChart.data.datasets[0].data[0] = myObj.data[0]+"%";
                        _xPointCounter = 1;
                        myLineChart.update();
                        console.log("refreshing chart");
                        return;
                    }
                    myLineChart.data.datasets[0].data[_xPointCounter++] = myObj.data[0]+"%";
                    console.log("updating chart");
                    myLineChart.update();
                }
            };
            xhttp.open("GET", "/ajax/{{ $stn->id }}", true);
            xhttp.send();
        }

        loadData();
        setInterval(function(){
            loadData(); // this will run after every 5 seconds
        }, 5000);
    });

</script>

@endsection
