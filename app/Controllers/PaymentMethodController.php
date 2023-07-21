<?php

namespace App\Controllers;

use App\Exceptions\DataTypeException;
use App\Exceptions\InvalidNameException;
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
    $model = null;
    try {
      $model = PaymentMethodModel::fromArray($data);
      $this->service->create($model);
      $this->setToast("Guardado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/paymentmethods');
    } catch (InvalidNameException $e) {
      $this->renderFormWithError(
        form: "create", 
        errorMessage: $e->getMessage(), 
        formData: $model);
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
      $model = PaymentMethodModel::fromArray($data);
      $this->service->update($model);
      $this->setToast("Guardado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/paymentmethods');
    } catch (InvalidNameException $e) {
      $formData = $this->service->getById($model->getId());
      $this->renderFormWithError(
        form: "create", 
        errorMessage: $e->getMessage(), 
        formData: $formData);
    } catch (DataTypeException $e) {
      throw new \Exception("Se esperaba una instancia de PaymentMethodModel");
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