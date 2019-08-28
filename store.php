<?php
ini_set('error_reporting', E_ALL);

$pdo = new PDO("mysql:host=localhost; dbname=tasks", "root", "");
$sql = "INSERT INTO data (name, text) VALUES (:name, :text)";
$statement = $pdo->prepare($sql);
$result = $statement->execute($_POST);

header("Location: /");


