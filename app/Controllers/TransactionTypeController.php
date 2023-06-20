<?php

namespace App\Controllers;

use App\Models\TransactionTypeModel;

class TransactionTypeController extends BaseController
{
  public function index()
  {

  }

  public function show()
  {

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