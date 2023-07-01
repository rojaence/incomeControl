<?php

namespace App\Controllers;

use App\Models\TransactionTypeModel;
use Utils\TemplateRenderer;
use Utils\ToastTrait;
use Constants\ToastType;
use Utils\Toast;

class TransactionTypeController extends BaseController
{
  use ToastTrait;
  private $templateRenderer;
  
  public function index()
  {    
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type");
    $query->execute();
    $rows = $query->fetchAll(\PDO::FETCH_ASSOC);
    $transactionTypes = array_map(function ($row) { return TransactionTypeModel::fromArray($row); }, $rows);
    $toast = $this->getToast();
    echo $this->templateRenderer->render("transactiontypes::index", ["transactionTypes" => $transactionTypes, "toast" => $toast]);
  }

  public function show($id)
  {
    $query = $this->dbConnection->prepare("SELECT * FROM transaction_type WHERE id = :id");
    $query->execute([ 'id' => $id ]);
    $row = $query->fetch(\PDO::FETCH_ASSOC);
    $transactionType = TransactionTypeModel::fromArray($row);
    return $transactionType;
  }

  public function create() {
    echo $this->templateRenderer->render('transactiontypes::create');
  }

  public function store($data)
  {
    if ($data instanceof TransactionTypeModel)
    {
      if (empty($data->getName())) {
        echo $this->templateRenderer->render("transactiontypes::create", ['formError' => 'El nombre no puede estar vacío']);
      } else if ($this->isDuplicateName(name: $data->getName(), id: $data->getId())) {
        echo $this->templateRenderer->render("transactiontypes::create", ['formError' => 'Ya existe un registro con el nombre proporcionado']);
      } else {
        $query = $this->dbConnection->prepare("INSERT INTO transaction_type (name, description, state) VALUES (:name, :description, :state)");
        $name = $data->getName();
        $description = $data->getDescription();
        $state = $data->getState();
        $query->bindParam(":name", $name, \PDO::PARAM_STR);
        $query->bindParam(":description", $description, \PDO::PARAM_STR);
        $query->bindParam(":state", $state, \PDO::PARAM_BOOL);
        $query->execute();
        $data->setId($this->dbConnection->lastInsertId());
        $this->setToast("Agregado correctamente", ToastType::SUCCESS);
        header("location: transactiontypes");
      }
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

  public function edit($id) {
    $data = $this->show($id);
    echo $this->templateRenderer->render("transactiontypes::edit", ["transactionType" => $data]);
  }

  public function update($data)
  {
    if (empty($data->getName())) {
      echo $this->templateRenderer->render('transactiontypes::edit', ['formError' => "El nombre no puede estar vacío", "transactionType" => $data]);
    } else if ($this->isDuplicateName($data->getName(), $data->getId())) {
      echo $this->templateRenderer->render("transactiontypes::edit", ['formError' => 'Ya existe un registro con el nombre proporcionado', "transactionType" => $data]);
    } else {
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
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      header("location: transactiontypes");
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