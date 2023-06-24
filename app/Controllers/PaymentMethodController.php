<?php

namespace App\Controllers;

use App\Models\PaymentMethodModel;

class PaymentMethodController extends BaseController
{
  public function index()
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method");
    $query->execute();

    $paymentMethods = $query->fetchAll(\PDO::FETCH_ASSOC);
    $paymentMethods = array_map(function ($paymentMethod) {
      return PaymentMethodModel::fromArray($paymentMethod);
    }, $paymentMethods);
    require "../resources/views/paymentmethods/index.php";
  }

  public function show($id): object
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method WHERE id = :id");
    $query->bindValue(':id', $id, \PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(\PDO::FETCH_ASSOC);

    return PaymentMethodModel::fromArray($result);

    // return new PaymentMethodModel(id: $result['id'], name: $result['name'], description: $result['description']);
  }

  public function store($data)
  {
    if ($data instanceof PaymentMethodModel) {
      // MySQLi
      /* $query = $this->dbConnection->prepare("INSERT INTO payment_method (name, description) VALUES (?, ?);");
      $name = $data->getName();
      $description = $data->getDescription();
      $query->bind_param("ss", $name, $description);
      $query->execute(); */

      // PDO
      $query = $this->dbConnection->prepare("INSERT INTO payment_method (name, description) VALUES (:name, :description)");
      $name = $data->getName();
      $description = $data->getDescription();
      $query->bindParam(":name", $name, \PDO::PARAM_STR);
      $query->bindParam(":description", $description, \PDO::PARAM_STR);
      $query->execute();
      $data->setId($this->dbConnection->lastInsertId());
    } else {
      throw new \Exception("El tipo de data debe ser una instancia de PaymentMethodModel");
    }
  }

  public function destroy($id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM payment_method WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function update($data)
  {
    if ($data instanceof PaymentMethodModel)
    {
      $query = $this->dbConnection->prepare("UPDATE payment_method SET name = :name, description = :description WHERE id = :id");
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
      throw new \InvalidArgumentException("El tipo de data debe ser una instancia de PaymentMethodModel");
    }
  }
}