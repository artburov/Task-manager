<?php
$config = include __DIR__ . "/../database/config.php";
include __DIR__. "/../database/QueryBuilder.php";
include __DIR__ . "/../database/Connection.php";

return new QueryBuilder( Connection ::make($config['database']) );