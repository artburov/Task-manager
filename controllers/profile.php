<?php
include __DIR__ . "/../functions/session_begin.php";
$db = include __DIR__ . "/../database/start.php";

//Получение из базы по id пути для картинки и передача его в сессию
$avatar = $db -> pathToImage( 'auth', $_SESSION['id'] );
$_SESSION['avatar_image'] = $avatar['image'];

include __DIR__ . "/../view/profile.view.php";
