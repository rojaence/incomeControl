<?php

namespace App\Controllers;

use App\Models\TransactionTypeModel;

class TransactionTypeController extends BaseController
{
  public function index()
  {    
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type");
    $query->execute();
    $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
    $transactionTypes = array_map(function ($row) { return TransactionTypeModel::fromArray($row); }, $rows);
    require '../resources/views/transactiontypes/index.php';
  }

  public function show($id)
  {
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type WHERE id = :id");
    $query->execute([ 'id' => $id ]);
    $row = $query->fetch(\PDO::FETCH_ASSOC);
    $transactionType = TransactionTypeModel::fromArray($row);
    return $transactionType;
    // return new TransactionTypeModel(id: $transactionType['id'], name: $transactionType['name'], description: $transactionType['description']);
  }

  public function store($data)
  {
    if ($data instanceof TransactionTypeModel)
    {
      $query = $this->dbConnection->prepare("INSERT INTO transaction_type (name , description) VALUES (:name, :description)");
      $name = $data->getName();
      $description = $data->getDescription();
      $query->bindParam(":name", $name, \PDO::PARAM_STR);
      $query->bindParam(":description", $description, \PDO::PARAM_STR);
      $query->execute();
      $data->setId($this->dbConnection->lastInsertId());
    } else {
      throw new \Exception("El tipo de data debe ser una instancia de TransactionTypeModel");
    }
  }

  public function destroy($id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM transaction_type WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function update($data)
  {
    if ($data instanceof TransactionTypeModel)
    {
      $query = $this->dbConnection->prepare("UPDATE transaction_type SET name = :name, description = :description WHERE id = :id");
      $id = $data->getId();
      $name = $data->getName();
      $description = $data->getDescription();
      $query->bindParam(':name', $name, \PDO::PARAM_STR);
      $query->bindParam(':description', $description, \PDO::PARAM_STR);
      $query->bindParam(':id', $id, \PDO::PARAM_INT);
      $query->execute();
    }
    else 
    {
      throw new \InvalidArgumentException("El tipo de data debe ser una instancia de TransactionTypeModel");
    }
  }
}