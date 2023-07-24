<?php

namespace App\Controllers;

use App\Models\IncomeModel;
use App\Services\IncomeService;
use App\Services\PaymentMethodService;
use App\Services\TransactionTypeService;
use App\Exceptions\DataTypeException;
use App\Exceptions\IdNotFoundException;
use Utils\ToastTrait;
use Constants\ToastType;
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
    $toast = $this->getToast();
    echo $this->templateRenderer->render("incomes::index", ["incomes" => $incomes,  "toast" => $toast]);
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
    try {
      $model = IncomeModel::fromArray($data);
      $this->incomeService->create($model);
      $this->setToast("Registrado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/incomes');
    } catch (\InvalidArgumentException $e) {
      $this->renderFormWithError(
        form: "create", 
        errorMessage: $e->getMessage(), 
        formData: $data);
    } catch ( DataTypeException $e ) {
      throw new \Exception($e->getMessage());
    }
  }

  public function update($data)
  {
    $id = $data['id'];
    try {
      $model = IncomeModel::fromArray($data);
      $this->incomeService->update($model);
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/incomes');
    } catch (\InvalidArgumentException $e) {
      $formData = $this->incomeService->getById($id);
      $this->renderFormWithError(
        form: "edit", 
        errorMessage: $e->getMessage(), 
        formData: $formData);
    } catch ( DataTypeException $e ) {
      throw new \Exception($e->getMessage());
    }
  }

  public function renderFormWithError(string $form, string $errorMessage, $formData)
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
    } catch (IdNotFoundException $e){
      $response = array('deleted' => false);
    }
    echo json_encode($response);
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }
}