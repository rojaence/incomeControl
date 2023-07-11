<?php

namespace App\Controllers;

use App\Exceptions\DataTypeException;
use App\Exceptions\DuplicateNameException;
use App\Exceptions\EmptyNameException;
use App\Models\PaymentMethodModel;
use App\Services\PaymentMethodService;
use Utils\TemplateRenderer;
use Utils\ToastTrait;
use Constants\ToastType;


class PaymentMethodController extends BaseController
{
  use ToastTrait;
  private $templateRenderer;
  private $service;

  public function __construct()
  {
    parent::__construct();
    $this->service = new PaymentMethodService();
  }

  /* public function index()
  {
    $query = $this->dbConnection->prepare("SELECT * FROM payment_method");
    $query->execute();
    $paymentMethods = $query->fetchAll(\PDO::FETCH_ASSOC);
    $paymentMethods = array_map(function ($paymentMethod) {
      return PaymentMethodModel::fromArray($paymentMethod);
    }, $paymentMethods);
    $toast = $this->getToast();
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
      // PDO
      if (empty($data->getName())) {
        echo $this->templateRenderer->render("paymentmethods::create", ['formError' => 'El nombre no puede estar vacío']);
      } else if ($this->isDuplicateName(name: $data->getName(), id: $data->getId())) {
        echo $this->templateRenderer->render("paymentmethods::create", ['formError' => 'Ya existe un registro con el nombre proporcionado']);
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
        $this->setToast("Guardado correctamente", ToastType::SUCCESS);
        header("location: /paymentmethods");
      }
    } else {
      throw new \Exception("Se esperaba una instancia de PaymentMethodModel");
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
    if (empty($data->getName())) {
      echo $this->templateRenderer->render('paymentmethods::edit', ['formError' => "El nombre no puede estar vacío", "paymentMethod" => $data]);
    } else if ($this->isDuplicateName($data->getName(), $data->getId())) {
      echo $this->templateRenderer->render("paymentmethods::edit", ['formError' => 'Ya existe un registro con el nombre proporcionado', "paymentMethod" => $data]);
    } else {
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
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      header("location: /paymentmethods");
    }
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }

  public function isDuplicateName(string $name, ?int $id = null) {
    $name = strtolower($name);
    $query = "SELECT * FROM payment_method WHERE LOWER(name) = :name";
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
  } */


  public function index()
  {
    $paymentMethods = $this->service->getAll();
    $toast = $this->getToast();
    echo $this->templateRenderer->render(
      "paymentmethods::index", 
      ['paymentMethods' => $paymentMethods, "toast" => $toast]
    );
  }

  public function show($id): object
  {
    $paymentMethod = $this->service->getById($id);
    return $paymentMethod;
  }

  public function edit($id)
  {
    $data = $this->service->getById($id);
    echo $this->templateRenderer->render(
      "paymentmethods::edit",
      ["paymentMethod" => $data]);
  }

  public function store($data)
  {
    try {
      $this->service->create($data);
      $this->setToast("Guardado correctamente", ToastType::SUCCESS);
      header("location: /paymentmethods");
    } catch (EmptyNameException $e) {
      echo $this->templateRenderer->render(
        "paymentmethods::create", 
        ['formError' => 'El nombre no puede estar vacío']);
    } catch (DuplicateNameException $e) {
      echo $this->templateRenderer->render(
        "paymentmethods::create", 
        ['formError' => 'Ya existe un registro con el nombre proporcionado']);
    } catch (DataTypeException $e) {
      throw new \Exception("Se esperaba una instancia de PaymentMethodModel");
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
    try {
      $this->service->update($data);
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      header("location: /paymentmethods");
    } catch (EmptyNameException $e) {
      $formData = $this->service->getById($data->getId());
      echo $this->templateRenderer->render(
        'paymentmethods::edit',
        ['formError' => "El nombre no puede estar vacío",
        "paymentMethod" => $formData]);
    } catch (DuplicateNameException $e) {
      $formData = $this->service->getById($data->getId());
      echo $this->templateRenderer->render(
        "paymentmethods::edit",
        ['formError' => 'Ya existe un registro con el nombre proporcionado',
        "paymentMethod" => $formData]);
    } catch (DataTypeException $e) {
      throw new \Exception("Se esperaba una instancia de PaymentMethodModel");
    }
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }
}