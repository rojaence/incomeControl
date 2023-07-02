<?php

namespace App\Repositories;
use Database\PDO\Connection;

class TransactionTypeRepository
{
  private $dbConnection;
  
  public function __construct()
  {
    $connection = Connection::getInstance()->getConnection();
    $this->dbConnection = $connection;
  }

  public function getTransactionTypeNameById(int $id)
  {
    $query = $this->dbConnection->prepare("SELECT name FROM transaction_type WHERE id = :id LIMIT 1");
    $query->bindParam(":id", $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    return $result["name"] ?? null;
  }
}