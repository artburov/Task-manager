<?php
include __DIR__ . "/../functions/session_begin.php";
$db = include __DIR__ . "/../database/start.php";

$email = $_POST['email'];
$password = $_POST['password'];

//Hashing user's password by default bcrypt algorithm
$password_hash = password_hash( $_POST['password'], PASSWORD_DEFAULT );

//Validate email address
$email_validate = filter_var( "$email", FILTER_VALIDATE_EMAIL );

//Redirect default URL for header() func to login page
$url_case = 'login';

//Cookies setting and destruction
if (isset( $_POST['remember'] ) == 1) {
    setcookie( "auth_cookie[email]", "$email", time() + 3600 * 24 * 7 );
    setcookie( "auth_cookie[password]", "$password_hash", time() + 3600 * 24 * 7 );
} else {
    setcookie( "auth_cookie[email]", "" );
    setcookie( "auth_cookie[password]", "" );
}

//Validation section for email and password fields filling
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

//Prepared authentication array for query in DB
$authentication = [
    'email'    => $_POST['email'],
    'password' => $password_hash
];

/* Validation of email and password  credentials within DB at Login page */
$validation_result = $db -> validationEmailAndPassword( 'auth', $authentication['email'], $authentication['password'] );

if (!$validation_result) {
    $_SESSION['login_email'] = 'E-mail не найден';
    goto end;
}
if (!password_verify( $password, $validation_result[0]['password'] )) {
    $_SESSION['login_password'] = 'Пароль неверный';
    goto end;
}

//Some useful session's IDs which are need in other pages
if ($validation_result) {
    $_SESSION['id'] = $validation_result[0]['id'];
    $_SESSION['user'] = $validation_result[0]['user'];
    $_SESSION['email_valid'] = $authentication['email'];
    $_SESSION['password_valid'] = $authentication['password'];

    $url_case = '../';
}

end:

header( "Location: /$url_case" );
