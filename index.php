<?php

require 'vendor/autoload.php';

use App\Controllers\PaymentMethodController;
use App\Controllers\TransactionTypeController;
use App\Models\PaymentMethodModel;
use App\Models\TransactionTypeModel;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
  $payment_method_controller = new PaymentMethodController();  
  $transaction_type_controller = new TransactionTypeController();

  // $payment_method_controller->store(new PaymentMethodModel(name: "example"));

  /* $transaction_type1 = new TransactionTypeModel(name:"retiro");
  $transaction_type_controller->store($transaction_type1);
  echo "El nuevo id para {$transaction_type1->getName()} es: {$transaction_type1->getId()}\n"; */

  // Listar todos los elementos
  /* $paymentMethods = $payment_method_controller->index();
  var_dump($paymentMethods); */

  /* $val1 = $payment_method_controller->show(id: 1);
  var_dump($val1); */

  // Listar todos los elementos
  $transactionTypes = $transaction_type_controller->index();
  var_dump($transactionTypes);

  $val1 = $transaction_type_controller->show(id: 1);
  var_dump($val1);

} catch(Exception $e)
{
  var_dump($e);
}
