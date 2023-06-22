<?php

namespace App\Controllers;

use App\Models\WithdrawalModel;

class WithdrawalController extends BaseController
{
  public function index(): array
  {
    $query = $this->dbConnection->prepare("SELECT * FROM withdrawal");
    $query->execute();
    $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
    $withdrawals = array_map(function ($row) {
      return WithdrawalModel::fromArray($row);
    }, $rows);
    return $withdrawals;
  }

  public function show($id): object
  {
    $query = $this->dbConnection->prepare("SELECT * FROM withdrawal WHERE id = :id");
    $query->execute([':id' => $id]);
    $row = $query->fetch(\PDO::FETCH_ASSOC);
    return WithdrawalModel::fromArray($row);
  }

  public function store($data)
  {
    if ($data instanceof WithdrawalModel)
    {
      $query = $this->dbConnection->prepare("INSERT INTO withdrawal (payment_method_id, transaction_type_id, date, amount, description) VALUES (:payment_method_id, :transaction_type_id, :date, :amount, :description)");
      $query->bindValue(":payment_method_id", $data->getPaymentMethodId(), \PDO::PARAM_INT);
      $query->bindValue(":transaction_type_id", $data->getTransactionTypeId(), \PDO::PARAM_INT);
      $query->bindValue(":date", $data->getDate(), \PDO::PARAM_STR);
      $query->bindValue(":amount", $data->getAmount(), \PDO::PARAM_STR);
      $query->bindValue(":description", $data->getDescription(), \PDO::PARAM_STR);
      $query->execute();
      $data->setId($this->dbConnection->lastInsertId());
    } else {
      throw new \Exception("El tipo de data debe ser una instancia de WithdrawalModel");
    }
  }

  public function destroy($id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM withdrawal WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }
}