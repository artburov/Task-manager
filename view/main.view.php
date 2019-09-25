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
    <link href="../public/css/app.css" rel="stylesheet">

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="/">
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

                    <?php if (isset( $auth_data['user'] )) { ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"
                               role="button"><?= $auth_data['user']; ?></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/profile">Профиль</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/controllers/log_out.php">Выход</a>
                            </div>
                        </li>

                    <? } else { ?>

                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="../controllers/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../controllers/register.php">Register</a>
                        </li>
                    <? } ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Комментарии</h3></div>

                        <div class="card-body">

                            <!-- Success flash message -->
                            <?php if ( isset( $_SESSION['message'] ) ) : ?>
                            <div class="alert alert-success" role="alert">
                                <? echo $_SESSION['message'];
                                endif;
                                unset( $_SESSION['message'] ); ?>

                                <!-- Danger flash message -->
                                <?php if ( isset ( $_SESSION['message_danger'] ) ) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <? echo $_SESSION['message_danger'];
                                    endif;
                                    unset( $_SESSION['message_danger'] ); ?>
                                </div>

                                <!-- Loop shows all comments -->
                                <?php foreach ( $posts as $comment ): ?>
                                    <div class="media">
                                        <img src="
                                        <? if (file_exists( __DIR__ . "/../" . $comment["image"] )) {
                                            echo $comment["image"];
                                        } else {
                                            echo 'img/no-user.jpg';
                                        } ?> " class="mr-3" alt="..." width="64"
                                             height="64">
                                        <div class="media-body">
                                            <h5 class="mt-0"><?= $comment["user"]; ?></h5>
                                            <span><small><?= $comment["date"]; ?></small></span>
                                            <p>
                                                <?= $comment["text"]; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!--Warning message about authorisation need before comment-->
                            <?php if ( !$_SESSION and !isset( $_COOKIE["auth_cookie"]["email"] ) ) { ?>
                            <div class="alert alert-primary" style="margin-top: 20px; role=" alert
                            ">
                            Чтобы оставить комментарий <a href="/login" class="href">авторизуйтесь</a>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="col-md-12" style="margin-top: 4px;">
                        <div class="card">
                            <div class="card-header"><h3>Оставить комментарий</h3></div>

                            <div class="card-body">
                                <form action="../controllers/store.php" method="post">

                                    <!--User name for comment-->
                                    <?php if (isset( $auth_data['user'] )) { ?>
                                        <div class="form-group">
                                            <input type="hidden" name="name" value="<?= $auth_data['user']; ?>">
                                        </div>
                                    <? } else { ?>

                                        <!--User name for comment if no session data exist-->
                                        <div class="form-group">
                                            <label for="exampleFormControlTextarea1">Имя</label>
                                            <input name="name" class="form-control" id="exampleFormControlTextarea1"/>
                                        </div>
                                    <?php } ?>

                                    <div class="form-group">
                                        <label for="exampleFormControlTextarea1">Сообщение</label>
                                        <textarea name="text" class="form-control" id="exampleFormControlTextarea1"
                                                  rows="3"></textarea>
                                    </div>
                                    <?php if (isset( $auth_data['user'] )) { ?>
                                        <button type="submit" class="btn btn-success">Отправить</button>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-danger"
                                                onclick="window.location.href='/login'">Авторизуйтесь
                                        </button>
                                    <?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>