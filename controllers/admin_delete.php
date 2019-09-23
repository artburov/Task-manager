<?php
$db = include __DIR__ . "/../database/start.php";

$id = $_POST['id'];

$db -> adminDeletePost( 'data', $id );

header( "Location: /admin" );
