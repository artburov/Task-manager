<?php
include __DIR__ . "/../functions/functions.php";


$routes = [
    "/"             => __DIR__ . "/../controllers/homepage.php",
    "/admin"        => __DIR__ . "/../controllers/admin.php",
    "/registration" => __DIR__ . "/../controllers/register.php",
    "/login"        => __DIR__ . "/../controllers/login.php",
    "/profile"      => __DIR__ . "/../controllers/profile.php"
];

$route = $_SERVER['REQUEST_URI'];

if (array_key_exists( $route, $routes )) {
    include $routes[$route];
    exit;
} else {
    dd( 404 );
}

