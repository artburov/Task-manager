<?php
include __DIR__ . "/../functions/functions.php";
$db = include __DIR__ . "/../database/start.php";

$name = $_POST['name'];
$text = $_POST['text'];

//Validation for creation of post
if (empty( $name ) AND empty( $text )) {
    $_SESSION['message_danger'] = 'Отсутствуют имя и сообщение';
    goto end;
} elseif (empty( $name )) {
    $_SESSION['message_danger'] = 'Отсутствует имя';
    goto end;
} elseif (empty( $text )) {
    $_SESSION['message_danger'] = 'Отсутствует сообщение';
    goto end;
} elseif (ctype_space ($text)) {
    $_SESSION['message_danger'] = 'Пробелы это не сообщение ';
    goto end;
} else {

    $db -> create( 'data', [
        'user'    => $_POST['name'],
        'text'    => $_POST['text'],
        'image'   => 'img/no-user.jpg',
        'date'    => dateToday(),
        'hidden'  => '0',
        'user_id' => $_SESSION['id']
    ] );
}

end:

header( "Location: /" );
