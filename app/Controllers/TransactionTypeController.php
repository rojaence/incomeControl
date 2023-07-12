<?php

namespace App\Controllers;

use App\Exceptions\DataTypeException;
use App\Exceptions\DuplicateNameException;
use App\Exceptions\EmptyNameException;
use App\Models\TransactionTypeModel;
use App\Services\TransactionTypeService;
use Utils\TemplateRenderer;
use Utils\ToastTrait;
use Constants\ToastType;

class TransactionTypeController extends BaseController
{
  use ToastTrait;
  private $templateRenderer;
  private $service;

  public function __construct()
  {
    parent::__construct();
    $this->service = new TransactionTypeService();
  }
  
  public function index()
  {    
    $transactionTypes = $this->service->getAll();
    $toast = $this->getToast();
    echo $this->templateRenderer->render("transactiontypes::index", ["transactionTypes" => $transactionTypes, "toast" => $toast]);
  }

  public function show($id)
  {
    $transactionType = $this->service->getById($id);
    return $transactionType;
  }

  public function create() {
    echo $this->templateRenderer->render('transactiontypes::create');
  }

  public function store($data)
  {
    try {
      $this->service->create($data);
      $this->setToast("Guardado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/transactiontypes');
    } catch (EmptyNameException $e) {
      $this->renderFormWithError(
        form: "create", 
        errorMessage: "El nombre no puede estar vacío", 
        formData: $data);
    } catch (DuplicateNameException $e) {
      $this->renderFormWithError(
        form: "create", 
        errorMessage: "Ya existe un registro con el nombre '{$data->getName()}'", 
        formData: $data);
    } catch (DataTypeException $e) {
      throw new \Exception("Se esperaba una instancia de TransactionTypeModel");
    }
  }

  public function destroy($id)
  {
  }

  public function edit($id) {
    $data = $this->show($id);
    echo $this->templateRenderer->render("transactiontypes::edit", ["transactionType" => $data]);
  }

  public function update($data)
  {
    try {
      $this->service->update($data);
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/transactiontypes');
    } catch (EmptyNameException $e) {
      $formData = $this->service->getById($data->getId());
      $this->renderFormWithError(
        form: "edit", 
        errorMessage: "El nombre no puede estar vacío", 
        formData: $formData);
    } catch (DuplicateNameException $e) {
      $formData = $this->service->getById($data->getId());
      $this->renderFormWithError(
        form: "edit", 
        errorMessage: "Ya existe un registro con el nombre '{$data->getName()}'", 
        formData: $formData);
    } catch (DataTypeException $e) {
      throw new \Exception($e->getMessage());
    } 
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }

  public function renderFormWithError(string $form, string $errorMessage, TransactionTypeModel $formData)
  {
    echo $this->templateRenderer->render(
      "transactiontypes::$form",
      ['formError' => $errorMessage,
      "transactionType" => $formData]);
  }
}