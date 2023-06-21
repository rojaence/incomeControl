<?php

namespace App\Controllers;

use App\Models\TransactionTypeModel;

class TransactionTypeController extends BaseController
{
  public function index()
  {    
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type");
    $query->execute();
    $transactionTypes = $query->fetchAll(\PDO::FETCH_ASSOC);
    return $transactionTypes;
  }

  public function show($id)
  {
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type WHERE id = :id");
    $query->execute([ 'id' => $id ]);
    $transactionType = $query->fetch(\PDO::FETCH_ASSOC);
    return new TransactionTypeModel(id: $transactionType['id'], name: $transactionType['name'], description: $transactionType['description']);
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
}