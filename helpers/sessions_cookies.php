<?php

if ($_SESSION) {
    $auth_data = authSession();
}

if (isset( $_COOKIE["auth_cookie"]["email"] )) {
    $data = authCookie();
    $auth_data = [ 'user' => $data[0]['user'] ];
}

if (!$_SESSION and !$_COOKIE) {
    header( 'Location: /login' );
//    $data = authCookie();
//    $auth_data = [ 'user' => $data[0]['user'] ];
}

//Validating authorisation via Session
function authSession()
{
    $auth_data = [
        'user'  => $_SESSION['user'],
        'email' => $_SESSION['email_valid']
    ];
    return $auth_data;
}

//Validating authorisation via Cookie
function authCookie()
{
    $pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
    $sql = 'SELECT * FROM auth WHERE email = :email_exist; password = :password_exist';
    $statement = $pdo -> prepare( $sql );
    $statement -> bindValue( ':email_exist', $_COOKIE["auth_cookie"]["email"] );
    $statement -> bindValue( ':password_exist', $_COOKIE["auth_cookie"]["password"] );
    $statement -> execute();
    $data = $statement -> fetchAll( PDO::FETCH_ASSOC );//IF no Session exists - user can be taken from DB when Cookies validation mechanism succeeded
    return $data;
}