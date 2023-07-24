<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Database\PDO\Connection;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$_ENV['environment'] = 'development';
date_default_timezone_set("America/Guayaquil");

class TestDataUtil
{
  // Function to truncate tables
  protected $dbConnection;

  public function __construct()
  {
    $connection = Connection::getInstance()->getConnection();
    $this->dbConnection = $connection;
  }

  public function truncateTable($tableName) {
    $disableFKCheck = $this->dbConnection->prepare("SET FOREIGN_KEY_CHECKS=0");
    $enableFKCheck = $this->dbConnection->prepare("SET FOREIGN_KEY_CHECKS=1");
    $query = "TRUNCATE TABLE {$tableName}";
    $disableFKCheck->execute();
    $this->dbConnection->exec($query);
    $enableFKCheck->execute(); 
  }

  // Function to insert data into payment_method table
  public function insertPaymentMethods() {

    $paymentMethods = [
      ['name' => 'Credit Card', 'description' => 'Payment with a credit card'],
      ['name' => 'PayPal', 'description' => 'Payment via PayPal'],
      ['name' => 'Bank Transfer', 'description' => 'Payment through bank transfer'],
      ['name' => 'Cash', 'description' => 'Payment in cash'],
      ['name' => 'Cryptocurrency', 'description' => 'Payment with cryptocurrency'],
    ];

    $stmt = $this->dbConnection->prepare("INSERT INTO payment_method (name, description) VALUES (:name, :description)");

    foreach ($paymentMethods as $method) {
      $stmt->execute($method);
    }
  }

  // Function to insert data into transaction_type table
  function insertTransactionTypes() {
    $transactionTypes = [
      ['name' => 'Sale', 'description' => 'Sale transaction'],
      ['name' => 'Refund', 'description' => 'Refund transaction'],
      ['name' => 'Withdrawal', 'description' => 'Withdrawal transaction'],
      ['name' => 'Deposit', 'description' => 'Deposit transaction'],
      ['name' => 'Transfer', 'description' => 'Transfer transaction'],
    ];

    $stmt = $this->dbConnection->prepare("INSERT INTO transaction_type (name, description) VALUES (:name, :description)");

    foreach ($transactionTypes as $type) {
      $stmt->execute($type);
    }
  }

  // Function to insert data into withdrawal and income tables
  function insertTransactions() {
    $paymentMethodsCount = 5; // Number of payment methods
    $transactionTypesCount = 5; // Number of transaction types

    $stmtWithdrawal = $this->dbConnection->prepare("INSERT INTO withdrawal (payment_method_id, transaction_type_id, date, amount, description) VALUES (:payment_method_id, :transaction_type_id, NOW(), :amount, :description)");
    $stmtIncome = $this->dbConnection->prepare("INSERT INTO income (payment_method_id, transaction_type_id, date, amount, description) VALUES (:payment_method_id, :transaction_type_id, NOW(), :amount, :description)");

    for ($i = 1; $i <= 10; $i++) {
      // Generate random payment method and transaction type IDs
      $paymentMethodId = rand(1, $paymentMethodsCount);
      $transactionTypeId = rand(1, $transactionTypesCount);
      $amount = rand(10, 1000); // Random amount between 10 and 1000
      $description = "Sample description for transaction {$i}";

      // Insert random records into withdrawal and income tables
      if ($i % 2 === 0) {
        $stmtWithdrawal->execute(['payment_method_id' => $paymentMethodId, 'transaction_type_id' => $transactionTypeId, 'amount' => $amount, 'description' => $description]);
      } else {
        $stmtIncome->execute(['payment_method_id' => $paymentMethodId, 'transaction_type_id' => $transactionTypeId, 'amount' => $amount, 'description' => $description]);
      }
    }
  }
}

// Truncate tables
$dataUtil = new TestDataUtil();
$dataUtil->truncateTable('withdrawal');
$dataUtil->truncateTable('income');
$dataUtil->truncateTable('payment_method');
$dataUtil->truncateTable('transaction_type');

// Insert data
$dataUtil->insertPaymentMethods();
$dataUtil->insertTransactionTypes();
$dataUtil->insertTransactions();

echo "Sample data inserted successfully!";



