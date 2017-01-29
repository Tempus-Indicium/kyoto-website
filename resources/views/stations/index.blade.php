<!DOCTYPE html>
<html lang="en">

<!-- schaduw/border van entry velden en sign in knop wann selected zijn nog blauw
+ services dat ie een dropdown voor help wordt-->

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!--internet explorer edge, for serious? -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Miss Hoornvled">

    <title>Kyoto University Disaster Prevention Research Institute</title>
    <link href="img/favicon3.ico" rel="icon" type="image/x-icon" />

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/landing-page.css" rel="stylesheet">
    <link href="css/page.css" rel="stylesheet">
    <link href="css/custom.css" type="text/css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
    <div class="container topnav">
        <div class="navbar-middle" id="navbar middle">
            <ul class="nav navbar-nav navbar-middle">
                <li>
                    <img src="img/LogoKyotoBigWhite.png" id="logo-page" alt="Kyoto logo">
                </li>
                <li>
                    <a href="#about">Map</a>
                </li>
                <li>
                    <a href="#services">Top 10 Asia</a>
                </li>
                <li>
                    <a href="#services">Help</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>


<!-- Header -->
<a name="about"></a>
<div class="intro-header">
    <h1>Top 10 stations based on visibility in Asia</h1>
    <div class="table">
        <table border="1">
            <tr> <!-- tr = row -->
                <th id="number">#</th> <!-- td = column -->
                <th>Country</th>
                <th>Station</th>
                <th>Visibility (in kms)</th>
            </tr>
            <?php $i = 1; ?>
            @foreach($stations as $station)
                <tr class="alt">
                    <td scope="row">{{ $i }}</td>
                    <td>{{ $station->country }}</td>
                    <td>{{ $station->station }}</td>
                    <td>{{ number_format($station->average,2) }}</td>
                </tr>
                <?php $i++ ?>
            @endforeach
        </table>
    </div>
</div>
<!-- /.container -->



<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-inline">
                    <li>
                        <a href="#">Home</a>
                    </li>
                    <li class="footer-menu-divider">&sdot;</li>
                    <li>
                        <a href="#services">Help</a>
                    </li>
                </ul>
                <p class="copyright text-muted small">Copyright &copy; Tempus Indicium 2015. All Rights Reserved</p>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>

