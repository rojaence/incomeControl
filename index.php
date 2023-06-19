<?php

require 'vendor/autoload.php';

use App\Controllers\PaymentMethodController;
use App\Models\PaymentMethodModel;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
  $payment_method_controller = new PaymentMethodController();  
  $payment_method_controller->store(new PaymentMethodModel(name: "CreditCard", description: "A valid credit card"));
  echo "Registro exitoso";
} catch(Exception $e)
{
  var_dump($e->getMessage());
}
