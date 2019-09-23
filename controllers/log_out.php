<?php
include __DIR__ . "/../functions/session_begin.php";

if ($_SESSION) {
    foreach ( $_SESSION as $key => $value ) {
        unset( $value );
    }
}

if (isset( $_SESSION )) {
    session_destroy();
}

setcookie( "auth_cookie[email]", "$email", time() - 3600 );
setcookie( "auth_cookie[password]", "$password", time() - 3600 );

header( "Location: /" );
