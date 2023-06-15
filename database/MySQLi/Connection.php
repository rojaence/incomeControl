<?php

require_once 'index.php';

$server = $_ENV['DB_HOST'];
$database = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

// conexión de forma procedural
// $mysqli = mysqli_connect($server, $database, $username, $password);

// conexión POO
$mysqli = new mysqli($server, $username, $password, $database);

// Comprobar conexión de forma procedural
/* if (!$mysqli) {
  die("FALLÓ LA CONEXIÓN: " . mysqli_connect_error());
} */

// Comprobar conexión POO
if ($mysqli->connect_errno) {
  die("FALLÓ LA CONEXIÓN: {$myqsli->connect_error()}");
}

// ESTA LÍNEA ES PARA QUE CADA QUE SE REALICEN CONSULTAS SE PUEDA USAR CUALQUIER CARACTER
$setnames = $mysqli->prepare("SET NAMES 'utf8'");
$setnames->execute();

// var_dump($setnames);