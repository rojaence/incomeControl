<?php

namespace App\Controllers;

use App\Models\WithdrawalModel;

class WithdrawalController extends BaseController
{
  public function index()
  {
    $query = $this->dbConnection->prepare("SELECT * FROM withdrawal");
    $query->execute();
    $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
    $withdrawals = array_map(function ($row) {
      return WithdrawalModel::fromArray($row);
    }, $rows);
    require "../resources/views/withdrawals/index.php";
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

  public function update($data)
  {
    if ($data instanceof WithdrawalModel)
    {
      $query = $this->dbConnection->prepare("UPDATE withdrawal SET payment_method_id = :payment_method_id, transaction_type_id = :transaction_type_id, date = :date, amount = :amount, description = :description WHERE id = :id");
      $paymentMethodId = $data->getPaymentMethodId();
      $transactionTypeId = $data->getTransactionTypeId();
      $date = $data->getDate();
      $amount = $data->getAmount();
      $description = $data->getDescription();
      $id = $data->getId();
      $query->bindValue(':payment_method_id', $paymentMethodId, \PDO::PARAM_INT);
      $query->bindValue(':transaction_type_id', $transactionTypeId, \PDO::PARAM_INT);
      $query->bindValue(':date', $date, \PDO::PARAM_STR);
      $query->bindValue(':amount', $amount, \PDO::PARAM_STR);
      $query->bindValue(':description', $description, \PDO::PARAM_STR);
      $query->bindValue(':id', $id, \PDO::PARAM_INT);
      $query->execute();
    }
    else 
    {
      throw new \InvalidArgumentException("El tipo de data debe ser una instancia de IncomeModel");
    }
  }
}