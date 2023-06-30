<?php

namespace App\Controllers;

use App\Models\PaymentMethodModel;
use Utils\TemplateRenderer;

class PaymentMethodController extends BaseController
{
  private $templateRenderer;

  public function index()
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method");
    $query->execute();

    $paymentMethods = $query->fetchAll(\PDO::FETCH_ASSOC);
    $paymentMethods = array_map(function ($paymentMethod) {
      return PaymentMethodModel::fromArray($paymentMethod);
    }, $paymentMethods);
    $toast = ["message" => "Mensaje de prueba"];
    echo $this->templateRenderer->render("paymentmethods::index", ['paymentMethods' => $paymentMethods, "toast" => $toast]);
  }

  public function show($id): object
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method WHERE id = :id");
    $query->bindValue(':id', $id, \PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(\PDO::FETCH_ASSOC);

    return PaymentMethodModel::fromArray($result);
  }

  public function edit($id)
  {
    $data = $this->show($id);
    echo $this->templateRenderer->render("paymentmethods::edit", ["paymentMethod" => $data]);
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
      if (empty($data->getName())) {
        echo $this->templateRenderer->render("paymentmethods::create", ['formError' => 'El nombre no puede estar vacío']);
      } else {
        $query = $this->dbConnection->prepare("INSERT INTO payment_method (name, description, state) VALUES (:name, :description, :state)");
        $name = $data->getName();
        $description = $data->getDescription();
        $state = $data->getState();
        $query->bindParam(":name", $name, \PDO::PARAM_STR);
        $query->bindParam(":description", $description, \PDO::PARAM_STR);
        $query->bindParam(":state", $state, \PDO::PARAM_BOOL);
        $query->execute();
        $data->setId($this->dbConnection->lastInsertId());
        header("location: paymentmethods");
      }
    } else {
      throw new \Exception("El tipo de data debe ser una instancia de PaymentMethodModel");
    }
  }

  public function create()
  {
    echo $this->templateRenderer->render('paymentmethods::create');
  }

  public function destroy($id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM payment_method WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function update($data)
  { 
    if ($data->getName() != "" ) {
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
      header("location: paymentmethods");
    }
    else 
    {
      echo $this->templateRenderer->render('paymentmethods::edit', ['formError' => "El nombre no puede estar vacío", "paymentMethod" => $data]);
    }
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }

  public function isDuplicateName(string $name, ?int $id = null) {
    $name = strtolower($name);
    $query = "SELECT * FROM transaction_type WHERE LOWER(name) = :name";
    $params = [":name" => $name];
    if ($id!= null) {
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