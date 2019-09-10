<?php

ini_set( 'error_reporting', E_ALL );
session_start();

//Hashing user's password by default bcrypt algorithm
$password_hash = password_hash( $_POST['password'], PASSWORD_DEFAULT );

$email = $_POST['email'];
$password = $_POST['password'];
if (isset($_POST['remember'])) {
    setcookie( "auth_cookie[email]", "$email", time() + 3600*24*7 );
    setcookie( "auth_cookie[password]", "$password_hash", time() + 3600*24*7 );
} else {
    setcookie( "auth_cookie[email]", "" );
    setcookie( "auth_cookie[password]", "" );
}

//Validate email address
$email_validate = filter_var( "$email", FILTER_VALIDATE_EMAIL );

//Redirect default URL for header() to login page
$url_case = 'login.php';

if (empty( $email )) {
    $_SESSION['login_email'] = 'Отсутствует e-mail';
    goto end;
}
if (!empty( $email )) {
    if ($email_validate == false) {
        $_SESSION['login_email'] = 'Введен некорректный формат e-mail';
        goto end;
    }
}
if (empty( $password )) {
    $_SESSION['login_password'] = 'Отсутствует пароль';
    goto end;
}
if (ctype_space( $password )) {
    $_SESSION['login_password'] = 'Введен некорректный пароль';
    goto end;
}
if (strlen( $password ) < 6) {
    $_SESSION['login_password'] = 'Пароль должен быть больше 6-ти символов';
    goto end;
}

//Prepared array for execute() instead bindParam() or just for inner IF statement
$authentication = [
    'email'    => $_POST['email'],
    'password' => $password_hash
];

/*Validate email and password*/
$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$email_check = 'SELECT * FROM auth WHERE email = :email_exist; password = :password_exist';
$sql_statement = $pdo -> prepare( $email_check );

$sql_statement -> bindValue( ':email_exist', $authentication['email'] );
$sql_statement -> bindValue( ':password_exist', $authentication['password'] );

$sql_statement -> execute();

$sql_result = $sql_statement -> fetchAll( PDO::FETCH_ASSOC );
if (!$sql_result) {
    $_SESSION['login_email'] = 'E-mail не найден';
    goto end;
}

if (!password_verify( $password, $sql_result[0]['password'] )) {
    $_SESSION['login_password'] = 'Пароль неверный';
    goto end;
}

if ($sql_result) {
    $_SESSION['id'] = $sql_result[0]['id'];
    $_SESSION['user'] = $sql_result[0]['user'];
    $_SESSION['email_valid'] = $authentication['email'];
    $_SESSION['password_valid'] = $authentication['password'];

    $url_case = 'index.php';
}

end:

header( "Location: /$url_case" );
