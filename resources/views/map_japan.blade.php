@extends('layouts.app')

@section('title', 'Stations in Japan')

@section('head')
    <style>
        #map {
            height: 600px;
            width: 400px
        }
        .container {
            height: 100%;
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>
    <script>
        function initMap() {
            var myLatlng = {lat: 35.652832, lng: 139.839478};

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
                center: myLatlng
            });

            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: 'Click for more info'
            });

            marker.addListener('click', function() {
                window.location = "station_information"
                /*window.open ("http://tempus-indicium.com/");*/
            });
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJqf7tm-OYfk2khlzqzQoXpOEKVN4eLxE&callback=initMap">
    </script>
@endsection