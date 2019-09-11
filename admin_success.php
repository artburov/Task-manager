<?php

ini_set( 'error_reporting', E_ALL );


if($_POST['id']) {
    $id = $_POST['id'];
}

if($_POST['allow'] == 0) {
    $allow = $_POST['allow'];

    $pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
    $sql_check = 'UPDATE data SET hidden = :hidden_change WHERE id= :id_user';
    $sql_statement = $pdo -> prepare( $sql_check );
    $sql_statement -> bindValue( ':hidden_change', $allow );
    $sql_statement -> bindValue( ':id_user', $id );
    $sql_statement -> execute();

    goto end;
}

end:

header( "Location: /admin.php" );

