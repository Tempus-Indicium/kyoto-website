@extends('layouts.app')
@section('opties')
    <link href="/css/mappage.css" rel="stylesheet">
@endsection
@section('title', 'Stations in Japan')

@section('content')
    <div id="map"></div>
    <script>
        function initMap() {
            var myLatlng = {lat: 38, lng: 137};

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 6,
                center: myLatlng
            });


            {!! $markers !!}
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap">
    </script>
@endsection