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
    <link href="../css/app.css" rel="stylesheet">
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
                    <!-- Authentication Links -->

                    <?php if (isset( $_SESSION['user'] )) { ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"
                               role="button"><?= $_SESSION['user']; ?></a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/controllers/profile.php">Профиль</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/controllers/log_out.php">Выход</a>
                            </div>
                        </li>

                    <? } else { ?>

                        <!-- Authentication Links on Default-->
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
                        <div class="card-header"><h3>Админ панель</h3></div>

                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Аватар</th>
                                    <th>Имя</th>
                                    <th>Дата</th>
                                    <th>Комментарий</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ( $posts as $status ) { ?>
                                    <tr>
                                    <td>
                                        <img src="<? if (file_exists( __DIR__ . "/../" . $status["image"] )) {
                                            echo $status["image"];
                                        } else {
                                            echo "../img/no-user.jpg";
                                        } ?> " alt="" class="img-fluid" width="64"
                                             height="64">
                                    </td>
                                    <td><?= $status['user']; ?></td>
                                    <td><?= $status['date']; ?></td>
                                    <td><?= $status['text']; ?>
                                    </td>
                                    <td>
                                    <?php if ($status['hidden'] == 0) { ?>
                                        <form action="../controllers/admin_deny.php" method="post">
                                            <input type="hidden" name="id" value="<?= $status['id']; ?>">
                                            <button type="submit" name="deny" class="btn btn-warning" value="1">
                                                Запретить
                                            </button>
                                        </form>
                                        <td>
                                            <form action="../controllers/admin_delete.php" method="post">
                                                <input type="hidden" name="id" value="<?= $status['id']; ?>">
                                                <button type="submit" name="delete" value="1" class="btn btn-danger"
                                                        onclick="return confirm('are you sure?')">Удалить
                                                </button>
                                            </form>
                                        </td>

                                    <?php } else { ?>
                                        <form action="../controllers/admin_success.php" method="post">
                                            <input type="hidden" name="id" value="<?= $status['id']; ?>">
                                            <button type="submit" name="allow" class="btn btn-success" value="0">
                                                Разрешить
                                            </button>
                                        </form>
                                        <td>
                                            <form action="../controllers/admin_delete.php" method="post">
                                                <input type="hidden" name="id" value="<?= $status['id']; ?>">
                                                <button type="submit" name="delete" class="btn btn-danger"
                                                        onclick="return confirm('are you sure?')" value="1">Удалить
                                                </button>
                                            </form>
                                        </td>
                                        </td>
                                        </tr>

                                    <?php }
                                } ?>

                                </tbody>
                            </table>
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