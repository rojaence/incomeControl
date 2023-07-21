<?php

namespace App\Controllers;

use App\Models\IncomeModel;
use App\Services\IncomeService;
use App\Services\PaymentMethodService;
use App\Services\TransactionTypeService;
use App\Exceptions\DataTypeException;
use Utils\ToastTrait;
use Constants\ToastType;
use LogicException;
use Utils\TemplateRenderer;

class IncomeController extends BaseController
{
  use ToastTrait;
  private $templateRenderer;
  private $incomeService;
  private $paymentMethodService;
  private $transactionTypeService;

  public function __construct()
  {
    parent::__construct();
    $this->incomeService = new IncomeService();
    $this->paymentMethodService = new PaymentMethodService();
    $this->transactionTypeService = new TransactionTypeService();
  }
  
  public function index()
  {
    $incomes = $this->incomeService->getAll();
    echo $this->templateRenderer->render("incomes::index", ["incomes" => $incomes]);
  }

  public function show($id): object
  {
    $result = $this->incomeService->getById($id);
    return $result;
  }

  public function create()
  {
    $paymentMethods = $this->paymentMethodService->getAll();
    $transactionTypes = $this->transactionTypeService->getAll();
    echo $this->templateRenderer->render("incomes::create", 
      ["paymentMethods" => $paymentMethods, 
      "transactionTypes" => $transactionTypes]);
  }

  public function edit($id)
  {
    $data = $this->incomeService->getById($id);
    $paymentMethods = $this->paymentMethodService->getAll();
    $transactionTypes = $this->transactionTypeService->getAll();
    echo $this->templateRenderer->render("incomes::edit", 
      ["income" => $data,
      "paymentMethods" => $paymentMethods, 
      "transactionTypes" => $transactionTypes]);
  }

  public function store($data)
  {
    $model = null;
    try {
      $model = IncomeModel::fromArray($data);
      $this->incomeService->create($model);
      $this->setToast("Registrado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/incomes');
    } catch (\InvalidArgumentException $e) {
      $this->renderFormWithError(
        form: "create", 
        errorMessage: $e->getMessage(), 
        formData: $model);
    } catch ( DataTypeException $e ) {
      throw new \Exception($e->getMessage());
    }
  }

  /* public function destroy($id)
  {
    try {
      $this->incomeService->delete($id);
      $this->setToast('Registro eliminado', ToastType::SUCCESS);
    } catch (\PDOException $e) {
      $this->setToast('Ha ocurrido un error', ToastType::DANGER);
    } finally {
      $this->redirectTo('/incomes');
    }
  } */

  public function update($data)
  {
    try {
      $this->incomeService->update($data);
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/incomes');
    } catch (\InvalidArgumentException $e) {
      $formData = $this->incomeService->getById($data->getId());
      $this->renderFormWithError(
        form: "edit", 
        errorMessage: $e->getMessage(), 
        formData: $formData);
    } catch ( DataTypeException $e ) {
      throw new \Exception($e->getMessage());
    }
  }

  public function renderFormWithError(string $form, string $errorMessage, IncomeModel $formData)
  {
    $paymentMethods = $this->paymentMethodService->getAll();
    $transactionTypes = $this->transactionTypeService->getAll();
    echo $this->templateRenderer->render(
      "incomes::$form",
      ['formError' => $errorMessage,
      "income" => $formData,
      "paymentMethods" => $paymentMethods,
      "transactionTypes" => $transactionTypes
    ]);
  }

  public function destroy($id) 
  {
    try {
      $this->incomeService->delete($id);
      $response = array('deleted' => true);
    } catch (\PDOException $e){
      $response = array('deleted' => false);
    }
    echo json_encode($response);
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }
}