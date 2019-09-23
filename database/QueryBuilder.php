<?php

class QueryBuilder
{
    protected $pdo;

    public function __construct( $pdo_value )
    {
        $this -> pdo = $pdo_value;
    }

    /* =====> MAIN PAGE DB QUERIES  <=====*/

    //Extracting all information from DB with certain table name where hidden status is 0 (visible)
    public function getAllPosts( $table )
    {
        /* $sql = "SELECT * FROM {$table} WHERE hidden = 0 ORDER BY id DESC"; */
        $sql = "SELECT * FROM {$table} JOIN auth ON data.user_id = auth.id WHERE hidden = 0 ORDER BY data.id DESC";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> execute();
        return $statement -> fetchAll( PDO::FETCH_ASSOC );
    }

    //Create a new comment post
    public function create( $table, $data )
    {
        $keys = implode( ', ', array_keys( $data ) );
        $tags = ':' . implode( ', :', array_keys( $data ) );

        $sql = "INSERT INTO {$table} ({$keys}) VALUES ({$tags})";
        $statement = $this -> pdo -> prepare( $sql );
        $result = $statement -> execute( $data );

        if ($result == true) {
            $_SESSION['message'] = 'Комментарий успешно добавлен';
        }
    }


    /* =====> BELOW PROFILE DB QUERIES <===== */

    //Update email field from Profile section based on ID
    public function updateEmail( $table, $email, $id )
    {
        //Необязательный блок, потому что в ключах только один e-mail, но конкатенация выглядит интересно
        //Можно было просто забиндить e-mail
        $keys = array_keys( $email );
        $string = '';
        foreach ( $keys as $key ) {
            $string .= $key . ' = :' . $key . ',';
        }
        $keys = rtrim( $string, ',' );


        $sql = "UPDATE {$table} SET {$keys} WHERE id = :id";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':email', implode( $email ) );
        $statement -> bindValue( ':id', implode( $id ) );
        $statement -> execute();
        $statement -> fetch( PDO::FETCH_ASSOC );

        //Для отображения измененного e-mail внутри value после нажатия кнопки Save profile
        $_SESSION['email_valid'] = implode( $email );
    }

    //Validation current password
    public function passwordValidation( $table, $id )
    {

        $sql = "SELECT * FROM {$table} WHERE id = :id ";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':id', $id );
        $statement -> execute();
        return $statement -> fetch( PDO::FETCH_ASSOC );
    }

    //Update password
    public function updatePassword( $table, $password, $id )
    {
        $sql = "UPDATE {$table} SET password = :password_change WHERE id = :id";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':password_change', $password );
        $statement -> bindValue( ':id', $id );
        $statement -> execute();
    }

    //Path of avatar image for session
    public function pathToImage( $table, $id )
    {
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':id', $id );
        $statement -> execute();
        return $statement -> fetch( PDO::FETCH_ASSOC );
    }

    //Validation of e-mail duplicate
    public function hasDuplicate( $table, $email )
    {

        $sql = "SELECT * FROM {$table} WHERE email=:email_duplicate";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':email_duplicate', $email );
        $statement -> execute();
        return $statement -> fetchAll( PDO::FETCH_ASSOC );
    }

    //Path to avatar image for view
    public function pathToAvatarImage( $table, $id )
    {
        $sql = "SELECT * FROM {$table} WHERE id=:id";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':id', $id );
        $statement -> execute();
        return $statement -> fetch( PDO::FETCH_ASSOC );
    }

    //Send image path on local server to DB
    public function sendImagePath( $table, $img_dir, $id )
    {
        $sql = "UPDATE {$table} SET image = :image_exist WHERE id = :id";
        $statement = $this -> pdo -> prepare( $sql );

        $statement -> bindValue( ':image_exist', $img_dir );
        $statement -> bindValue( ':id', $id );
        $statement -> execute();
        $statement -> fetch( PDO::FETCH_ASSOC );
    }


    /* =====> BELOW ADMIN PANEL DB QUERIES <===== */

    //Get all posts into main admin panel
    public function adminGetAllPosts( $table )
    {
        //Выборка из таблицы с коментариями data всех полей и из таблицы аутентификации auth поля с изображением,
        // в части JOIN уточняю идентификатор таблицы аутентификации auth для избавления от дубликатов строк при выводе
        $sql = "SELECT data.*, auth.image FROM {$table} JOIN auth ON data.user_id = auth.id ORDER BY data.id DESC";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> execute();
        return $data = $statement -> fetchAll( PDO::FETCH_ASSOC );
    }

    //Delete comment post from admin panel
    public function adminDeletePost( $table, $id )
    {
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':id', $id );
        $statement -> execute();
    }

    //Deny to display selected post
    public function adminDenyPost( $table, $id, $deny )
    {
        $sql = 'UPDATE data SET hidden = :hidden_change WHERE id= :id_user';
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':hidden_change', $deny );
        $statement -> bindValue( ':id_user', $id );
        $statement -> execute();
    }

    //Allow to display selected post
    public function adminAllowPost( $table, $id, $allow )
    {
        $sql = 'UPDATE data SET hidden = :hidden_change WHERE id= :id_user';
        $sql = $this -> pdo -> prepare( $sql );
        $sql -> bindValue( ':hidden_change', $allow );
        $sql -> bindValue( ':id_user', $id );
        $sql -> execute();
    }


    /* =====>BELOW REGISTRATION DB QUERIES <===== */

    //Check duplicate e-mail into DB
    public function checkDuplicateEmail( $table, $email )
    {
        $sql = "SELECT * FROM {$table} WHERE email = :email_duplicate";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':email_duplicate', $email );
        $statement -> execute();
        return $statement -> fetch( PDO::FETCH_ASSOC );
    }

    //No duplicate e-mail found then would be created a new user
    public function noDuplicateEmail( $table, $user, $password, $email, $image )
    {
        $sql = "INSERT INTO {$table} (user, password, email, image) VALUES (:name, :password, :email, :image)";
        $statement = $this -> pdo -> prepare( $sql );
        $statement -> bindValue( ':name', $user );
        $statement -> bindValue( ':password', $password );
        $statement -> bindValue( ':email', $email );
        $statement -> bindValue( ':image', $image );
        return $statement -> execute();
    }


    /* =====> BELOW LOGIN VALIDATION OF EMAIL & PASSWORD DB QUERIES <===== */

    public function validationEmailAndPassword( $table, $email, $pass )
    {
        $check = "SELECT * FROM {$table} WHERE email = :email_exist; password = :password_exist";
        $statement = $this -> pdo -> prepare( $check );

        $statement -> bindValue( ':email_exist', $email );
        $statement -> bindValue( ':password_exist', $pass );

        $statement -> execute();

        return $statement -> fetchAll( PDO::FETCH_ASSOC );

    }

}