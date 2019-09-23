<?php
$db = include __DIR__ . "/../database/start.php";

$posts = $db -> getAllPosts( 'data' );

include __DIR__ . "/../view/main.view.php";
