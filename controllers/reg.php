<?php
include __DIR__ . "/../functions/session_begin.php";
$db = include __DIR__ . "/../database/start.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirmation'];

//Hashing user's password by default bcrypt algorithm
$password_hash = password_hash( $password, PASSWORD_DEFAULT );

//Validate email address
$email_validate = filter_var( "$email", FILTER_VALIDATE_EMAIL );


$url_case = 'registration';

if (empty( $name )) {
    $_SESSION['message_name'] = 'Отсутствует имя';
    goto end;
}
if (ctype_space( $name )) {
    $_SESSION['message_name'] = 'Введенно некорректное имя';
    goto end;
}
if (empty( $email )) {
    $_SESSION['message_email'] = 'Отсутствует e-mail';
    goto end;
}
if (!empty( $email )) {
    if ($email_validate == false) {
        $_SESSION['message_email'] = 'Введен некорректный формат e-mail';
        goto end;
    }
}
if (empty( $password )) {
    $_SESSION['message_password'] = 'Отсутствует пароль';
    goto end;
}
if (ctype_space( $password )) {
    $_SESSION['message_password'] = 'Введен некорректный пароль';
    goto end;
}
if (strlen( $password ) < 6) {
    $_SESSION['message_password'] = 'Пароль должен быть больше 6-ти символов';
    goto end;
}
if ($password != $password_confirm) {
    $_SESSION['message_password'] = 'Пароль не совпадает';
    goto end;
}

//Prepared array from POST and hash
$registration = [
    'name'     => $_POST['name'],
    'email'    => $_POST['email'],
    'image'    => $_POST['first_avatar_img'],
    'password' => $password_hash
];

/*Prepared SQL block to check email duplicate into DB*/
$reg_email_duplicate = $db -> checkDuplicateEmail( 'auth', $registration['email'] );

if ($reg_email_duplicate) {
    $_SESSION['message_email'] = 'Найден дубликат e-mail';
    goto end;
} else {
    $db -> noDuplicateEmail( 'auth',
        $registration['name'],
        $registration['password'],
        $registration['email'],
        $registration['image'] );

    $url_case = 'login';
}

end:

header( "Location: /$url_case" );
