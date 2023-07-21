<?php

namespace App\Services;
use App\Models\TransactionTypeModel;
use App\Exceptions\InvalidNameException;
use App\Exceptions\DataTypeException;

class TransactionTypeService extends BaseService
{
  public function getTransactionTypeNameById(int $id)
  {
    $query = $this->dbConnection->prepare("SELECT name FROM transaction_type WHERE id = :id LIMIT 1");
    $query->bindParam(":id", $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    return $result["name"] ?? null;
  }

  public function getById(int $id): TransactionTypeModel
  {
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type WHERE id = :id");
    $query->bindValue(':id', $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC); 
    return TransactionTypeModel::fromArray($result);
  }

  public function getAll()
  {
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type");
    $query->execute();
    $transactionTypes = $query->fetchAll(\PDO::FETCH_ASSOC);
    $transactionTypes = array_map(function ($transactionType) {
      return TransactionTypeModel::fromArray($transactionType);
    }, $transactionTypes);
    return $transactionTypes;
  }

  public function create($data)
  {
    if (!$data instanceof TransactionTypeModel)
    {
      throw new DataTypeException('Se esperaba una instancia de TransactionTypeModel');
    }
    if (empty($data->getName())) {
      throw new InvalidNameException('El nombre no puede estar vacío');
    }
    if ($this->isDuplicateName(name: $data->getName(), id: $data->getId())) {
      throw new InvalidNameException("Ya existe un registro con el nombre '{$data->getName()}'");
    }
    $query = $this->dbConnection->prepare("INSERT INTO transaction_type (name, description, state) VALUES (:name, :description, :state)");
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
    if (!$data instanceof TransactionTypeModel)
    {
      throw new \Exception('Se esperaba una instancia de TransactionTypeModel');
    }
    if (empty($data->getName())) {
      throw new InvalidNameException('El nombre no puede estar vacío');
    }
    if ($this->isDuplicateName(name: $data->getName(), id: $data->getId())) {
      throw new InvalidNameException("Ya existe un registro con el nombre '{$data->getName()}'");
    }
    $query = $this->dbConnection->prepare("UPDATE transaction_type SET name = :name, description = :description, state = :state WHERE id = :id");
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
    $query = $this->dbConnection->prepare("DELETE FROM transaction_type WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function isDuplicateName(string $name, ?int $id = null) {
    $name = strtolower($name);
    $query = "SELECT * FROM transaction_type WHERE LOWER(name) = :name";
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