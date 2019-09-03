<?php
ini_set( 'error_reporting', E_ALL );
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comments</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                Project
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Register</div>
                        <div class="card-body">
                            <form method="POST" action="reg.php">

                                <!--Name field validation-->
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                    <?php if (isset( $_SESSION['message_name'] )) { ?>
                                        <div class="col-md-6">
                                            <input id="name" type="text"
                                                   class="form-control @error('name') is-invalid @enderror" name="name"
                                                   autofocus>

                                            <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo $_SESSION['message_name']; ?></strong>
                                                </span>
                                        </div>
                                        <? unset( $_SESSION['message_name'] ); ?>
                                    <?php } else { ?>
                                        <div class="col-md-6">
                                            <input name="name" id="name" type="text" class="form-control" autofocus>
                                        </div>
                                    <?php } ?>
                                </div>

                                <!--E-mail field validation-->
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail
                                        Address</label>
                                    <?php if (isset( $_SESSION['message_email'] )) { ?>

                                        <div class="col-md-6">
                                            <input id="email" type="text"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email">

                                            <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo $_SESSION['message_email']; ?></strong>
                                                </span>
                                        </div>
                                        <? unset( $_SESSION['message_email'] ); ?>
                                    <?php } else { ?>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email">
                                        </div>
                                    <?php } ?>
                                </div>

                                <!--Password field validation-->
                                <div class="form-group row">
                                    <label for="password"
                                           class="col-md-4 col-form-label text-md-right">Password</label>

                                    <?php if (isset( $_SESSION['message_password'] )) { ?>
                                        <div class="col-md-6">
                                            <input id="password" type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   name="password">

                                            <span class="invalid-feedback" role="alert">
                                                    <strong><?php echo $_SESSION['message_password']; ?></strong>
                                                </span>
                                        </div>
                                        <? unset( $_SESSION['message_password'] ); ?>
                                    <?php } else { ?>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control " name="password">
                                        </div>
                                    <?php } ?>
                                </div>

                                <!--password-confirm for compare-->
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm
                                        Password</label>
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>