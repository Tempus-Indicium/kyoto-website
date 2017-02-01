
        <!DOCTYPE html>
<html lang="en">

<!-- schaduw/border van entry velden en sign in knop wann selected zijn nog blauw -->

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!--internet explorer edge, for serious? -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Miss Hoornveld">

    <title>Tempus Indicium - Disaster Prevention Research Institute</title>
    <link href="img/favicon3.ico" rel="icon" type="image/x-icon" />

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/landing-page.css" rel="stylesheet">
    <link href="css/landing-custom.css" rel="stylesheet">
    <link href="css/custom.css" type="text/css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="img/LogoKyotoRood.png" id="logo" alt="Kyoto logo" height="42" width="42">
                <img src="img/LogoKyoto.jpeg" id="logo" alt="Kyoto logo" height="42" width="42">
                <a class="navbar-brand topnav" href="#">Kyoto University Disaster Prevention Institute</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#about">About</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Services<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Help</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#contact">Contact</a>
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
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <div class="logo">
                            <h1><img src="img/LogoKyotoBigRed.png" id="logo" alt="Kyoto logo">Kyoto University</h1>
                        </div>
                        <h3>Disaster Prevention Research Institute login</h3>
                        <hr class="intro-divider">
                        <div class="signin">
                            <!--<div class="panel-heading">Login</div>-->
                            <!--<div class="panel-body">-->
                                    <form class="form-signin" role="form" method="POST" action="{{ url('/login') }}">
                                        {{ csrf_field() }}
                                        <h2 class="form-signin-heading">Please sign in</h2>
                                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="inputemail" class="sr-only">Email Address</label>
                                            <!--<div class="col-md-6">-->
                                                <input id="inputemail" type="email" class="form-control" name="email" placeholder="Email adress" value="{{ old('email') }}" required autofocus>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            <!--</div>-->
                                        </div>
                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="inputpassword" class="sr-only">Password</label>
                                            <!--<div class="col-md-6">-->
                                                <input id="inputpassword" type="password" class="form-control" name="password" placeholder="Password" required>
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            <!--</div>-->
                                        </div>
                                        <div class="form-group">
                                                <div class="checkbox">
                                                    <label>
                                                        <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
                                                    </label>
                                                </div>
                                        </div>

                                        <div class="form-group">
                                            <!--<div class="col-md-8 col-md-offset-4">-->
                                                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

                                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                                    Forgot Your Password?
                                                </a>
                                           <!-- </div>-->
                                        </div>
                                    </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            <a href="#about">About</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="#services">Services</a>
                        </li>
                        <li class="footer-menu-divider">&sdot;</li>
                        <li>
                            <a href="#contact">Contact</a>
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