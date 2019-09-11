<?php
ini_set( 'error_reporting', E_ALL );
session_start();


$password = [
  'current' => $_POST['current'],
  'password' => $_POST['password'],
  'password_confirmation' => $_POST['password_confirmation']
];

//Hashing user's password by default bcrypt algorithm
$password_hash = password_hash( $password['password'], PASSWORD_DEFAULT );

//ID number from DB
$id_fromDB = $_SESSION['id'];

/*Validate email and password*/
$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$email_check = 'SELECT * FROM auth WHERE id = :user_id ';
$sql_statement = $pdo -> prepare( $email_check );
$sql_statement -> bindValue( ':user_id', $_SESSION['id'] );

$sql_statement -> execute();

$sql_result = $sql_statement -> fetchAll( PDO::FETCH_ASSOC );

if (empty( $password['password'] )) {
//    $_SESSION['password_change'] = 'Отсутствует пароль';
    goto end;
}

if (!password_verify( $password['current'], $sql_result[0]['password'] )) {
    $_SESSION['login_password_change'] = 'Пароль неверный';
    goto end;
}

if (ctype_space( $password['password'] )) {
    $_SESSION['password_change'] = 'Введен некорректный пароль';
    goto end;
}
if (strlen( $password['password'] ) < 6) {
    $_SESSION['password_change'] = 'Пароль должен быть больше 6-ти символов';
    goto end;
}
if ($password['password'] != $password['password_confirmation']) {
    $_SESSION['password_change'] = 'Пароль не совпадает';
    goto end;
}

$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql = 'UPDATE auth SET password = :password_change WHERE id= :id_user';
$statement = $pdo -> prepare( $sql );
$statement -> bindValue( ':password_change', $password_hash );
$statement -> bindValue( ':id_user', $id_fromDB );
$result = $statement -> execute();


end:
$_SESSION['message_done'] = 'Информация о пароле получена';
header( "Location: /profile.php" );