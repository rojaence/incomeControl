<?php

require "../vendor/autoload.php";

use App\Controllers\IncomeController;
use App\Controllers\PaymentMethodController;
use App\Controllers\TransactionTypeController;
use App\Controllers\WithdrawalController;
use Router\RouterHandler;
use Utils\TemplateRenderer;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
date_default_timezone_set("America/Guayaquil");

// OBTENER LA URL
$slug = $_GET["slug"];
$slug = explode("/", $slug);

$resource = $slug[0] === "" ? "/" : $slug[0];
$id = $slug[1] ?? null;

// Instancia del motor de plantillas
$templates = new League\Plates\Engine(__DIR__ . "/.." . '/resources/templates');
$templates->addFolder('paymentmethods', __DIR__ . "/.." . "/resources/templates/paymentmethods");
$templates->addFolder('transactiontypes', __DIR__ . "/.." . "/resources/templates/transactiontypes");
$templates->addFolder('incomes', __DIR__ . "/.." . "/resources/templates/incomes");
$templates->addFolder('withdrawals', __DIR__ . "/.." . "/resources/templates/withdrawals");
$homePage = new League\Plates\Template\Template($templates, 'home');

// Instancia del router
$router = new RouterHandler();

// Instancia TemplateRenderer
$templateRender = new TemplateRenderer($templates);

switch ($resource) {

  case '/':
    try {
      echo $homePage->render();
    } catch (Exception $e){
      print_r($e);
    }
    break;

  case "incomes":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(IncomeController::class, $templateRender, $id);
    break;

  case "withdrawals":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(WithdrawalController::class, $templateRender, $id);
    break;


  case "paymentmethods":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(PaymentMethodController::class, $templateRender, $id);
    break;

  case "transactiontypes":
    $method = $_POST["method"] ?? "GET";
    $router->setMethod($method);
    $router->setData($_POST);
    $router->route(TransactionTypeController::class, $templateRender, $id);
    break;

  default:
    echo "404 Not Found";
    break;

}
?>
