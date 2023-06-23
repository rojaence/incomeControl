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

  // $pm1 = $payment_method_controller->show(id: 1);
  // var_dump($pm1);

  // $tp1 = $transaction_type_controller->show(id: 1);
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


  // ACTUALIZAR UN REGISTRO
  /* $tt1 = new TransactionTypeModel(name: 'example', description: 'example description');
  $transaction_type_controller->store($tt1);
  $tt1->setName("example edit");
  $transaction_type_controller->update($tt1); */

  /* $pm1 = $payment_method_controller->show(id: 1);
  $pm1->setName("credit card");
  $payment_method_controller->update($pm1);
  var_dump($pm1); */

  
  // Actualizando incomes y withdrawals

  $incomeController = new IncomeController();
  $withdrawalController = new WithdrawalController();

  $payMethods = $payment_method_controller->index();
  $trTypes = $transaction_type_controller->index();

  /* $pmId = $payMethods[0]->getId();
  $ttId = $trTypes[0]->getId(); */

  /* $income1 = new IncomeModel(amount: 250, description: "Descripción de ejemplo para income de test", paymentMethodId: $payMethods[0]->getId(), transactionTypeId: $trTypes[0]->getId());
  $incomeController->store($income1);
  echo "Registro exitoso\n";
  echo "Nuevo id para income: {$income1->getId()}";

  $income1->setDescription("Descripción de ejempo para income de test modificada");
  var_dump($income1);
  echo "\n";
  $incomeController->update($income1);
  var_dump($income1); */

  // Borrando un income
  /* $incomes = $incomeController->index();
  $income1 = new IncomeModel(amount: 250, description: "Descripción de ejemplo para income de test", paymentMethodId: $payMethods[0]->getId(), transactionTypeId: $trTypes[0]->getId());
  $incomeController->store($income1);

  $incomeController->destroy($income1->getId());
  $incomes = $incomeController->index();
  var_dump($incomes); */


  // Borrando un withdrawal
  /* $withdrawals = $withdrawalController->index();
  $withdrawal1 = new WithdrawalModel(amount: 250, description: "Descripción de ejemplo para income de test", paymentMethodId: $payMethods[0]->getId(), transactionTypeId: $trTypes[0]->getId());
  $withdrawalController->store($withdrawal1);

  $withdrawalController->destroy($withdrawal1->getId());
  $withdrawals = $withdrawalController->index();
  var_dump($withdrawals);  */


  // EDITANDO UN WITHDRAWAL
  /* $withdrawal1 = $withdrawalController->show(3);
  $withdrawal1->setAmount(4500.45);
  $withdrawal1->setTransactionTypeId(5);
  $withdrawal1->setDate(date("Y-m-d\TH:i:s"));
  $withdrawalController->update($withdrawal1); */
  
} catch(Exception $e)
{
  var_dump($e);
  // var_dump($e->getTrace());
}

