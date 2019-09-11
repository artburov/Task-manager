<?php
ini_set( 'error_reporting', E_ALL );
session_start();

$name = $_POST['name'];
$text = $_POST['text'];

if (empty( $name ) AND empty( $text )) {
    $_SESSION['message_danger'] = 'Отсутствуют имя и сообщение';
    goto end;
} elseif (empty( $name )) {
    $_SESSION['message_danger'] = 'Отсутствует имя';
    goto end;
} elseif (empty( $text )) {
    $_SESSION['message_danger'] = 'Отсутствует сообщение';
    goto end;
} else {
    $pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
    $sql = "INSERT INTO data (user, text, image, date, hidden) VALUES (:name, :text, 'img/no-user.jpg', date_format(current_date(), '%d/%m/%Y'), '0')";
    $statement = $pdo -> prepare( $sql );
    $result = $statement -> execute( $_POST );
    if ($result == true) {
        $_SESSION['message'] = 'Комментарий успешно добавлен';
    }
}
end:

header( "Location: /" );
