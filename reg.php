<?php

ini_set( 'error_reporting', E_ALL );
session_start();

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirmation'];

//Hashing user's password by default bcrypt algorithm
$password_hash = password_hash( $password, PASSWORD_DEFAULT );

//Validate email address
$email_validate = filter_var( "$email", FILTER_VALIDATE_EMAIL );


$url_case = 'register.php';

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

//Prepared array for execute() instead bindParam()
$registration = [
    'name'     => $_POST['name'],
    'email'    => $_POST['email'],
    'password' => $password_hash
];

/*Prepared SQL block to check email duplicate into DB*/
$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql_check = 'SELECT * FROM auth WHERE email=:email_duplicate';
$sql_statement = $pdo -> prepare( $sql_check);
$sql_statement->bindValue(':email_duplicate', $email);
$sql_statement->execute();

$sql_result =  $sql_statement -> fetch();
//var_dump($sql_result==true);die;

if ($sql_result) {
    $_SESSION['message_email'] = 'Найден дубликат e-mail';
    goto end;
} else {
    $sql = "INSERT INTO auth (user, password, email) VALUES (:name, :password, :email)";
    $statement = $pdo -> prepare( $sql );

/*    $statement -> bindParam( "name", $name );
    $statement -> bindParam( "password", $password_hash );
    $statement -> bindParam( "email", $email );
    $result = $statement -> execute();*/

    $result = $statement -> execute( $registration );
    $url_case = 'login.php';
    goto end;

}

end:

header( "Location: /$url_case" );
