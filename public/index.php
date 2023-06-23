<?php

require "../vendor/autoload.php";

// OBTENER LA URL

$slug = $_GET["slug"];
$slug = explode("/", $slug);

$resource = $slug[0] === "" ? "/" : $slug[0];
$id = $slug[1] ?? null;

switch ($resource) {

  case '/':
    echo "Estás en la front page";
    break;

  case "incomes":
    echo "Estás en las ingresos ";
    break;

  case "withdrawals":
    echo "Estás en los egresos ";
    break;

  default:
    echo "404 Not Found";
    break;

}

/* echo '<pre>';
var_dump($slug);
echo '</pre>'; */

?>