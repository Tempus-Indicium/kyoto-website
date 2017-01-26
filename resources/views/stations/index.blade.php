@extends('layouts.app')

@section("content")
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<h1>The top 10 stations based on visibility in Asia.</h1>
<table class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Country</th>
        <th>Station</th>
        <th>Visibility</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
@foreach($stations as $station)
<tr>
    <td scope="row">{{ $i }}</td>
    <td>{{ $station->country }}</td>
    <td>{{ $station->station }}</td>
    <td>{{ number_format($station->average,2) }}</td>
</tr>
    <?php $i++ ?>
@endforeach
</tbody>
</table>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
@endsection
