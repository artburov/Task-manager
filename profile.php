<?php
ini_set( 'error_reporting', E_ALL );
session_start();

//Validating authorisation via Session
if ($_SESSION) {
    $auth_data = [
        'user'  => $_SESSION['user'],
        'email' => $_SESSION['email_valid']
    ];
}
//var_dump($_SESSION);die;
$id_fromDB = $_SESSION['id'];

//Получение из базы по ID пути для картинки и передача его в сессию
$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql = 'SELECT * FROM auth WHERE id = ' . $id_fromDB . ' ';
$statement = $pdo -> prepare( $sql );
$statement -> execute();
$data = $statement -> fetchAll( PDO::FETCH_ASSOC );

//Image link from DB to Session
$image_avatar = $data[0]['image'];
$_SESSION['avatar_image'] = $image_avatar;


//Если есть в директории сервера изображение, то отпраить его путь в БД
if (isset( $_SESSION['image_dir'] )) {

    $image_dir = $_SESSION['image_dir'];

    $pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
    $sql = 'UPDATE auth SET image = :image_exist WHERE id = :user_id';
    $statement = $pdo -> prepare( $sql );

    $statement -> bindValue( ':image_exist', $image_dir );
    $statement -> bindValue( ':user_id', $id_fromDB );

    $statement -> execute();
    $data = $statement -> fetchAll( PDO::FETCH_ASSOC );

}

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
            <a class="navbar-brand" href="/index.php">
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
                                <a class="dropdown-item" href="/profile.php">Профиль</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout.php">Выход</a>
                            </div>
                        </li>

                    <? } else { ?>

                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
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
                        <div class="card-header"><h3>Профиль пользователя</h3></div>

                        <div class="card-body">
                            <?php if (isset( $_SESSION['message_ok'] )) { ?>
                                <div class="alert alert-success" role="alert">
                                    <?= $_SESSION['message_ok']; ?>
                                    <?php unset( $_SESSION['message_ok'] ); ?>
                                </div>
                            <?php } ?>

                            <form action="profile_handler.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Name</label>
                                            <input type="text" class="form-control" name="name"
                                                   id="exampleFormControlInput1" value="<?= $auth_data['user']; ?>"
                                                   readonly>

                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Email</label>
                                            <?php if (isset( $_SESSION['message_email'] )) { ?>
                                                <input type="email" class="form-control is-invalid" name="email"
                                                       id="exampleFormControlInput1"
                                                       value="<?= $auth_data['email']; ?>">
                                                <span class="text text-danger">
                                                    <?= $_SESSION['message_email']; ?>
                                                </span>
                                                <?php unset( $_SESSION['message_email'] ); ?>
                                            <?php } else { ?>

                                                <input type="email" class="form-control" name="email"
                                                       id="exampleFormControlInput1"
                                                       value="<?= $auth_data['email']; ?>">
                                            <?php } ?>

                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Аватар</label>
                                            <input type="file" class="form-control" name="image"
                                                   id="exampleFormControlInput1">
                                        </div>
                                    </div>
                                    <?php if (!empty( $_SESSION ['avatar_image'] )) { ?>
                                        <div class="col-md-4">
                                            <img src="<?= $image_avatar ?>" alt="" class="img-fluid">
                                        </div>
                                    <?php } elseif (empty( $_SESSION ['avatar_image'] )) { ?>
                                        <div class="col-md-4">
                                            <img src="img/no-user.jpg" alt="" class="img-fluid">
                                        </div>
                                    <?php } ?>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-warning">Edit profile</button>

                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Безопасность</h3></div>

                        <div class="card-body">
                            <div class="alert alert-success" role="alert">
                                Пароль успешно обновлен
                            </div>

                            <form action="/profile/password" method="post">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Current password</label>
                                            <input type="password" name="current" class="form-control"
                                                   id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">New password</label>
                                            <input type="password" name="password" class="form-control"
                                                   id="exampleFormControlInput1">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Password confirmation</label>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                   id="exampleFormControlInput1">
                                        </div>

                                        <button class="btn btn-success">Submit</button>
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

