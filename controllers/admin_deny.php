<?php
$db = include __DIR__ . '/../database/start.php';

$id = $_POST['id'];
$deny = $_POST['deny'];

$db -> adminDenyPost( 'data', $id, $deny );

header( "Location: /admin" );

