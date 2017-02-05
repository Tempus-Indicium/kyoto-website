@extends('layouts.app')
@section('opties')
    <link href="css/helppage.css" rel="stylesheet">
@endsection
@section('content')
    <h3>Help page</h3>
    <h1>Having trouble? We're here to help!</h1>
    <div class="help">
        <h4>Forgotten password?</h4>
        <p>The login screen has a button to reset forgotten passwords. To get to the login screen, you have to be logged out.</p>
        <h4>A problem with the website?</h4>
        <p>Is the website not working? Try to reload the page. If the problems persist, please mail using the email address below.</p>
        <p>We are constantly working to improve the website, but we cannot guarantee that the website works on every webbrowser. Our supported webbrowsers are Google Chrome, Mozilla Firefox, Microsoft Internet Explorer and Apple Safari.</p>
        <h4>Documentation</h4>
        <p>This website is using parts of the Laravel PHP framework and the Bootstrap framework. For the documentation on Laravel, we refer you to the official Laravel website. For the documentation on Bootstrap, we refer you to the official Twitter Bootstrap website.</p>
        <h4>The API</h4>
        <p>The API requires the same account as the website. It is used on this website to request weather data from Japanese Stations and get the top 10 stations based on visibility in Asia of today. The data can also be delivered in xml format.</p>
        <br>
        <br>
        <p>If you have further questions you can contact us at:</p>
        <p>help@tempus-indicium.com</p>
    </div>
@endsection