<?php

namespace App\Services;
use App\Models\PaymentMethodModel;
use App\Exceptions\EmptyNameException;
use App\Exceptions\DuplicateNameException;
use App\Exceptions\DataTypeException;

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
    if (!$data instanceof PaymentMethodModel)
    {
      throw new DataTypeException('Se esperaba una instancia de PaymentMethodModel');
    }
    if (empty($data->getName())) {
      throw new EmptyNameException('El nombre no puede estar vacio');
    }
    if ($this->isDuplicateName(name: $data->getName(), id: $data->getId())) {
      throw new DuplicateNameException("Ya existe un registro con el nombre proporcionado");
    }
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
    if (!$data instanceof PaymentMethodModel)
    {
      throw new \Exception('Se esperaba una instancia de PaymentMethodModel');
    }
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

  public function isDuplicateName(string $name, ?int $id = null) {
    $name = strtolower($name);
    $query = "SELECT * FROM payment_method WHERE LOWER(name) = :name";
    $params = [":name" => $name];
    if ($id != null) {
      $query.= " AND id <> :id";
      $params[":id"] = $id;
    }
    $query.= " LIMIT 1";
    $query = $this->dbConnection->prepare($query);
    $query->execute($params);
    $count = $query->fetchColumn();  
    return $count > 0;
  }
}