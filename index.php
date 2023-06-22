<?php

require 'vendor/autoload.php';

use App\Controllers\PaymentMethodController;
use App\Controllers\TransactionTypeController;
use App\Controllers\IncomeController;
use App\Controllers\WithdrawalController;
use App\Models\IncomeModel;
use App\Models\PaymentMethodModel;
use App\Models\TransactionTypeModel;
use App\Models\WithdrawalModel;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

date_default_timezone_set("America/Guayaquil");

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

  // echo "\n-----------------\n";

  /* $val1 = $payment_method_controller->show(id: 1);
  var_dump($val1); */

  // Listar todos los elementos
  /* $transactionTypes = $transaction_type_controller->index();
  var_dump($transactionTypes); */

  // echo "\n-----------------\n";

  $pm1 = $payment_method_controller->show(id: 1);
  // var_dump($pm1);

  $tp1 = $transaction_type_controller->show(id: 1);
  // var_dump($tp1);

  // Insertar un Income
  /* $incomeController = new IncomeController();
  $income1 = new IncomeModel(amount: 250, description: "Descripción de ejemplo", paymentMethodId: $pm1->getId(), transactionTypeId: $tp1->getId());
  $incomeController->store($income1);
  echo "Registro exitoso\n";
  echo "Nuevo id para income: {$income1->getId()}"; */

  // Insertar un Withdrawal
  /* $withdrawalController = new WithdrawalController();
  $withdrawal1 = new WithdrawalModel(amount: 250, description: "Descripción de ejemplo", paymentMethodId: $pm1->getId(), transactionTypeId: $tp1->getId());
  $withdrawalController->store($withdrawal1);
  echo "Registro exitoso\n";
  echo "Nuevo id para withdrawal: {$withdrawal1->getId()}"; */


  // ELIMINAR REGISTROS
  // INSERSIÓN DE REGISTROS DE PRUEBA
  /* $pm2 = new PaymentMethodModel('example', 'example description');
  $payment_method_controller->store($pm2);

  $list = $payment_method_controller->index();
  var_dump($list);

  
  echo "El nuevo elemento {$pm2->getName()} registrado tiene el id: {$pm2->getId()}";
  echo "\n________________________________________________________________\n";
  $payment_method_controller->destroy($pm2->getId());
  $pm2 = null;

  $list = $payment_method_controller->index();
  var_dump($list); */

} catch(Exception $e)
{
  var_dump($e);
  var_dump($e->getTrace());
}

