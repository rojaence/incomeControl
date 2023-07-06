<?php

namespace App\Repositories;
use Database\PDO\Connection;
use App\Models\PaymentMethodModel;

class PaymentMethodRepository
{
  private $dbConnection;
  
  public function __construct()
  {
    $connection = Connection::getInstance()->getConnection();
    $this->dbConnection = $connection;
  }

  public function getPaymentMethodNameById(int $id)
  {
    $query = $this->dbConnection->prepare("SELECT name FROM payment_method WHERE id = :id LIMIT 1");
    $query->bindParam(":id", $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    return $result["name"] ?? null;
  }

  public function getAll()
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method");
    $query->execute();
    $paymentMethods = $query->fetchAll(\PDO::FETCH_ASSOC);
    $paymentMethods = array_map(function ($paymentMethod) {
      return PaymentMethodModel::fromArray($paymentMethod);
    }, $paymentMethods);
    return $paymentMethods;
  }
}