<?php
include __DIR__."/../functions/session_begin.php";
include __DIR__."/../helpers/sessions_cookies.php";

function dd($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
    die;
}

function dateToday() {
    return date( 'd/m/Y');
}
