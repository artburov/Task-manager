<?php
ini_set( 'error_reporting', E_ALL );

$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql = "INSERT INTO data (user, text, image, date) VALUES (:name, :text, 'img/no-user.jpg', date_format(current_date(), '%d/%m/%Y'))";
$statement = $pdo -> prepare( $sql );
$result = $statement -> execute( $_POST );

header( "Location: /" );
