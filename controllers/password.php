<?php
include __DIR__ . "/../functions/session_begin.php";
$db = include __DIR__ ."/../database/start.php";

//Array values for password input fields
$password = [
    'current'               => $_POST['current'],
    'password'              => $_POST['password'],
    'password_confirmation' => $_POST['password_confirmation']
];

//Hashing user's password by default bcrypt algorithm
$password_hash = password_hash( $password['password'], PASSWORD_DEFAULT );

//ID number from DB
$id_fromDB = $_SESSION['id'];

/*Password validation*/
$password_validation = $db -> passwordValidation( 'auth', $id_fromDB );


//Below all input password fields validation
if (empty( $password['current'] )) {
    goto end;
}

if (!empty( $password['current'] )) {
    if (!empty( $password['current'] )) {
        if (strlen( $_POST['current'] ) < 6) {
            $_SESSION['login_password_change'] = 'Введенный пароль для проверки слишком короткий';
            goto end;
        }
    }
}

if (!password_verify( $password['current'], $password_validation['password'] )) {
    $_SESSION['login_password_change'] = 'Введен неверный текущий пароль';
    goto end;
}

if (isset( $password['password'] )) {
    if (!empty( $password['password'] )) {
        if (strlen( $password['password'] ) < 6) {
            $_SESSION['password_change'] = 'Пароль должен быть больше 6-ти символов';
            goto end;
        }
        if (ctype_space( $password['password'] )) {
            $_SESSION['password_change'] = 'Введен некорректный пароль';
            goto end;
        }
        if ($password['password'] != $password['password_confirmation']) {
            $_SESSION['password_change'] = 'Новый пароль не совпадает';
            goto end;
        }

    } else {
        if (empty( $password['password_confirmation'] )) {
            $_SESSION['password_change'] = 'Введите новый пароль';
            goto end;
        }
    }
}

//Update password
$db -> updatePassword( 'auth', $password_hash, $id_fromDB );

end:
$_SESSION['message_done'] = 'Информация о пароле получена';
header( "Location: /profile" );