<?php

ini_set( 'error_reporting', E_ALL );
//session_start();

//var_dump( $_POST );
//die;

$name = $_POST['name'];
$password = $_POST['password'];
$email = $_POST['email'];
$email_confirm = $_POST['password_confirmation'];


$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql = "INSERT INTO auth (user, password, email) VALUES (:name, :password, :email)";
$statement = $pdo -> prepare( $sql );
$statement->bindParam("name", $name);
$statement->bindParam("password", $password);
$statement->bindParam("email", $email);

$result = $statement -> execute();

header( "Location: /" );
