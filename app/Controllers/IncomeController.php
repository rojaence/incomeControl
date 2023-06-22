<?php

namespace App\Controllers;

use App\Models\IncomeModel;

class IncomeController extends BaseController
{
  public function index(): array
  {
    $query = $this->dbConnection->prepare("SELECT * FROM income");
    $query->execute();
    $incomes = $query->fetchAll(\PDO::FETCH_ASSOC);
    $incomes = array_map(function ($income) { return IncomeModel::fromArray($income); }, $incomes);
    return $incomes;
  }

  public function show($id): object
  {
    $query = $this->dbConnection->prepare("SELECT * FROM income WHERE id = :id");
    $query->bindValue(":id", $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    return IncomeModel::fromArray($result);
  }

  public function store($data)
  {
    if ($data instanceof IncomeModel)
    {
      $query = $this->dbConnection->prepare("INSERT INTO income (payment_method_id, transaction_type_id, date, amount, description) VALUES (:payment_method_id, :transaction_type_id, :date, :amount, :description)");
      $query->bindValue(":payment_method_id", $data->getPaymentMethodId(), \PDO::PARAM_INT);
      $query->bindValue(":transaction_type_id", $data->getTransactionTypeId(), \PDO::PARAM_INT);
      $query->bindValue(":date", $data->getDate(), \PDO::PARAM_STR);
      $query->bindValue(":amount", $data->getAmount(), \PDO::PARAM_STR);
      $query->bindValue(":description", $data->getDescription(), \PDO::PARAM_STR);
      $query->execute();
      $data->setId($this->dbConnection->lastInsertId());
    } else {
      throw new \Exception("El tipo de data debe ser una instancia de IncomeModel");
    }
  }

  public function destroy($id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM income WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }
}