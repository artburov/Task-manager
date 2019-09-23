<?php
$db = include __DIR__ . "/../database/start.php";

$id = $_POST['id'];
$allow = $_POST['allow'];

$db -> adminAllowPost( 'data', $id, $deny );

header( "Location: /admin" );

