<?php
include __DIR__ . "/../functions/functions.php";
include __DIR__ . "/../image/ImageManager.php";
$db = include __DIR__ . "/../database/start.php";

//Need to check email in update func
$name = $_POST['name'];
$email = $_POST['email'];

//ID number from DB
$id_fromDB = $_SESSION['id'];

$auth_data = [
    'user'  => $_POST['name'],
    'email' => $_POST['name']
];

//Получение пути к изображению аватара
$avatar_path = $db -> pathToAvatarImage( 'auth', $_SESSION['id'] );

$file_img = $avatar_path['image'];
$_SESSION['avatar_image'] = $file_img;

//Если добавляется изображение, то имя файла разбивается на две части - имя и расширение
if ($_FILES['image']['error'] == 0) {
    $_SESSION['image_name'] = $_FILES['image']['name'];
    $_SESSION['image_tmp_name'] = $_FILES['image']['tmp_name'];

    //Название до точки переименовывается в уникальное имя, затем отдельно к имени конкатенируется расширение
    $image_name = ImageManager ::getImageNameWithoutExtension( $_SESSION['image_name'] );
    $image_name_ext = ImageManager ::getImageExtensionWithoutName( $_SESSION['image_name'] );
    $uniq_image_name = ImageManager ::getUniqImageName( $image_name );
    $full_uniq_image_name = ImageManager ::getFullUniqImageFileName( $uniq_image_name, $image_name_ext );

    //Удаление существующего файла изображения
    $image_file_location = __DIR__ . "/../" . $file_img;
    if (ImageManager ::validateImageLocation( $image_file_location )) {
        ImageManager ::deleteImageFile( $image_file_location );
    }

    //Перемещает загруженный файл в необходимую папку по полному пути проекта
    $image_path_DB = __DIR__ . "/../public/avatar/";
    ImageManager ::moveUploadedImageFile( $_SESSION['image_tmp_name'],
        $image_path_DB . $full_uniq_image_name );

    //Путь на локальном сервере к изображению аватара, передается дальше в БД
    //Выполняется обрезание части полного пути к файлу до названия папки с изображением
    $shorted_path = $image_path_DB . $full_uniq_image_name;
    $cutImg_path = ImageManager ::cutImagePathForDB( $shorted_path, "public" );
    $_SESSION['image_dir'] = $cutImg_path;

}

//Если есть в директории сервера изображение, то отпрвить его путь в БД
if (isset( $_SESSION['image_dir'] )) {

    $db -> sendImagePath( 'auth', $_SESSION['image_dir'], $_SESSION['id'] );
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
    goto end;
}
if (!empty( $email )) {
    if ($email_validate == false) {
        $_SESSION['message_email'] = 'Введен некорректный формат e-mail';
        goto end;
    }
}

//Проверка изменения e-mail на уже существующий в БД
$profile_duplicate = $db -> hasDuplicate( 'auth', $email );

if ($profile_duplicate) {
    $_SESSION['message_email'] = 'Найден дубликат e-mail';
    goto end;
}

//По ID выполняю модификацию E-mail поля в секции Профиля
$db -> updateEmail( 'auth',
    [
        'email' => $_POST['email']
    ],
    [
        'id' => $id_fromDB
    ]
);

end:
$_SESSION['message_ok'] = 'Профиль успешно обновлен';
header( "Location: /profile" );