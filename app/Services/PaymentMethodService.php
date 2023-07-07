<?php

namespace App\Services;
use App\Models\PaymentMethodModel;

class PaymentMethodService extends BaseService
{
  public function getPaymentMethodNameById(int $id)
  {
    $query = $this->dbConnection->prepare("SELECT name FROM payment_method WHERE id = :id LIMIT 1");
    $query->bindParam(":id", $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    return $result["name"] ?? null;
  }

  public function getAll(): array
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method");
    $query->execute();
    $paymentMethods = $query->fetchAll(\PDO::FETCH_ASSOC);
    $paymentMethods = array_map(function ($paymentMethod) {
      return PaymentMethodModel::fromArray($paymentMethod);
    }, $paymentMethods);
    return $paymentMethods;
  }

  public function getById(int $id): PaymentMethodModel
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method WHERE id = :id");
    $query->bindValue(':id', $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC); 
    return PaymentMethodModel::fromArray($result);
  }

  public function create($data)
  {
    $query = $this->dbConnection->prepare("INSERT INTO payment_method (name, description, state) VALUES (:name, :description, :state)");
    $name = $data->getName();
    $description = $data->getDescription();
    $state = $data->getState();
    $query->bindParam(":name", $name, \PDO::PARAM_STR);
    $query->bindParam(":description", $description, \PDO::PARAM_STR);
    $query->bindParam(":state", $state, \PDO::PARAM_BOOL);
    $query->execute();
    $data->setId($this->dbConnection->lastInsertId());
  }

  public function update($data)
  {
    $query = $this->dbConnection->prepare("UPDATE payment_method SET name = :name, description = :description, state = :state WHERE id = :id");
    $id = $data->getId();
    $name = $data->getName();
    $description = $data->getDescription();
    $state = $data->getState();
    $query->bindParam(':name', $name, \PDO::PARAM_STR);
    $query->bindParam(':description', $description, \PDO::PARAM_STR);
    $query->bindParam(':state', $state, \PDO::PARAM_BOOL);
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function delete(int $id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM payment_method WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }
}