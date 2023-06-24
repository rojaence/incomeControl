<?php

require "../vendor/autoload.php";

use App\Controllers\IncomeController;
use App\Controllers\PaymentMethodController;
use App\Controllers\TransactionTypeController;
use App\Controllers\WithdrawalController;
use Router\RouterHandler;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

date_default_timezone_set("America/Guayaquil");


// OBTENER LA URL

$slug = $_GET["slug"];
$slug = explode("/", $slug);

$resource = $slug[0] === "" ? "/" : $slug[0];
$id = $slug[1] ?? null;


// Instancia del router
$router = new RouterHandler();

switch ($resource) {

  case '/':
    echo "Estás en la front page";
    break;

  case "incomes":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(IncomeController::class, $id);
    break;

  case "withdrawals":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(WithdrawalController::class, $id);
    break;


  case "paymentmethods":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(PaymentMethodController::class, $id);
    break;

  case "transactiontypes":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(TransactionTypeController::class, $id);
    break;

  default:
    echo "404 Not Found";
    break;

}

/* echo '<pre>';
var_dump($slug);
echo '</pre>'; */

?>