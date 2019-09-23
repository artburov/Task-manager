<?php
$db = include __DIR__ . "/../database/start.php";

$posts = $db -> adminGetAllPosts( 'data' );

include __DIR__ . "/../view/admin.view.php";
