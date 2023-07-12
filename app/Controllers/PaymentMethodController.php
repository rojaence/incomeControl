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
      $this->redirectTo('/paymentmethods');
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
      throw new \Exception("Se esperaba una instancia de PaymentMethodModel");
    }
  }

  public function create()
  {
    echo $this->templateRenderer->render('paymentmethods::create');
  }

  public function destroy($id)
  {
  }

  public function update($data)
  { 
    try {
      $this->service->update($data);
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/paymentmethods');
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

  public function renderFormWithError(string $form, string $errorMessage, PaymentMethodModel $formData)
  {
    echo $this->templateRenderer->render(
      "paymentmethods::$form",
      ['formError' => $errorMessage,
      "paymentMethod" => $formData]);
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }
}