<?php

require_once 'index.php';

$server = $_ENV['DB_HOST'];
$database = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

$connection = new PDO("mysql:host=$server;dbname=$database", $username, $password);

$setnames = $connection->prepare("SET NAMES 'utf8'");
$setnames->execute();