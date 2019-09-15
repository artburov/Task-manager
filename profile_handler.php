<?php
ini_set( 'error_reporting', E_ALL );
session_start();

//ID number from DB
$id_fromDB = $_SESSION['id'];

$name = $_POST['name'];
$email = $_POST['email'];


//Receive path to avatar image
$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql_check = 'SELECT * FROM auth WHERE id=:id_user';
$sql_statement = $pdo -> prepare( $sql_check );
$sql_statement -> bindValue( ':id_user', $id_fromDB );
$sql_statement -> execute();
$sql_result = $sql_statement -> fetch( PDO::FETCH_ASSOC );

$file_img = $sql_result['image'];


//Delete image from folder into server path
if ($_FILES['image']['error'] == 0) {
    $_SESSION['image_name'] = $_FILES['image']['name'];
    $_SESSION['image_tmp_name'] = $_FILES['image']['tmp_name'];


//    var_dump(file_exists( $path_to_avatar . $file_img))
    if (file_exists( $file_img )) {
        unlink( $file_img );

    }
    move_uploaded_file( $_SESSION['image_tmp_name'], 'avatar/' . $_FILES['image']['name'] . '' );

    //Путь на локальном сервере к изображению аватара, передается дальше в profile.php
    $_SESSION['image_dir'] = 'avatar/' . $_FILES['image']['name'] . '';

}


if ($_FILES['image']['error'] == 4) {
// если не обновлялось изображение
}

//Validate email address
$email_validate = filter_var( "$email", FILTER_VALIDATE_EMAIL );


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

//Prepared array for execute() instead bindParam()
$registration = [
    'name'  => $_POST['name'],
    'email' => $_POST['email'],
];

/*Prepared SQL block to check email duplicate into DB*/
$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql_check = 'SELECT * FROM auth WHERE email=:email_duplicate';
$sql_statement = $pdo -> prepare( $sql_check );
$sql_statement -> bindValue( ':email_duplicate', $email );
$sql_statement -> execute();
$sql_result = $sql_statement -> fetchAll( PDO::FETCH_ASSOC );


if ($sql_result) {
    $_SESSION['message_email_dublicate'] = 'Найден дубликат e-mail';
    goto end;
}

//По ID выполняю модификацию E-mail поля из Profile
$pdo = new PDO( "mysql:host=localhost; dbname=tasks", "root", "" );
$sql_check = 'UPDATE auth SET email = :email_new WHERE id= :id_new';
$sql_statement = $pdo -> prepare( $sql_check );
$sql_statement -> bindValue( ':email_new', $email );
$sql_statement -> bindValue( ':id_new', $id_fromDB );
$sql_statement -> execute();
$sql_result = $sql_statement -> fetchAll( PDO::FETCH_ASSOC );

$_SESSION['email_valid'] = $email;

end:
$_SESSION['message_ok'] = 'Профиль успешно обновлен';
header( "Location: /profile.php" );