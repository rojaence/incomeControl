<?php

namespace App\Controllers;

use App\Models\WithdrawalModel;
use App\Services\WithdrawalService;
use App\Services\PaymentMethodService;
use App\Services\TransactionTypeService;
use App\Exceptions\DataTypeException;
use Utils\ToastTrait;
use Constants\ToastType;
use Utils\TemplateRenderer;

class WithdrawalController extends BaseController
{
  
  use ToastTrait;
  private $templateRenderer;
  private $withdrawalService;
  private $paymentMethodService;
  private $transactionTypeService;

  public function __construct()
  {
    parent::__construct();
    $this->withdrawalService = new WithdrawalService();
    $this->paymentMethodService = new PaymentMethodService();
    $this->transactionTypeService = new TransactionTypeService();
  }
  
  public function index()
  {
    $withdrawals = $this->withdrawalService->getAll();
    $toast = $this->getToast();
    echo $this->templateRenderer->render("withdrawals::index", ["withdrawals" => $withdrawals, "toast" => $toast]);
  }

  public function show($id): object
  {
    $query = $this->dbConnection->prepare("SELECT * FROM withdrawal WHERE id = :id");
    $query->execute([':id' => $id]);
    $row = $query->fetch(\PDO::FETCH_ASSOC);
    return WithdrawalModel::fromArray($row);
  }

  public function create() 
  {
    $paymentMethods = $this->paymentMethodService->getAll();
    $transactionTypes = $this->transactionTypeService->getAll();
    echo $this->templateRenderer->render("withdrawals::create", 
      ["paymentMethods" => $paymentMethods, 
      "transactionTypes" => $transactionTypes]);
  }

  public function store($data)
  {
    try {
      $model = WithdrawalModel::fromArray($data);
      $this->withdrawalService->create($model);
      $this->setToast("Registrado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/withdrawals');
    } catch (\InvalidArgumentException $e) {
      $this->renderFormWithError(
        form: "create", 
        errorMessage: $e->getMessage(), 
        formData: $data);
    } catch ( DataTypeException $e ) {
      throw new \Exception($e->getMessage());
    }
  }

  public function edit($id)
  {
    $data = $this->withdrawalService->getById($id);
    $paymentMethods = $this->paymentMethodService->getAll();
    $transactionTypes = $this->transactionTypeService->getAll();
    echo $this->templateRenderer->render("withdrawals::edit", 
      ["withdrawal" => $data,
      "paymentMethods" => $paymentMethods, 
      "transactionTypes" => $transactionTypes]);
  }

  public function destroy($id)
  {
    try {
      $this->withdrawalService->delete($id);
      $response = array('deleted' => true);
    } catch (\PDOException $e){
      $response = array('deleted' => false);
    }
    echo json_encode($response);
  }

  public function update($data)
  {
    $id = $data['id'];
    try {
      $model = WithdrawalModel::fromArray($data);
      $this->withdrawalService->update($model);
      $this->setToast("Actualizado correctamente", ToastType::SUCCESS);
      $this->redirectTo('/withdrawals');
    } catch (\InvalidArgumentException $e) {
      $formData = $this->withdrawalService->getById($id);
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
      "withdrawals::$form",
      ['formError' => $errorMessage,
      "withdrawal" => $formData,
      "paymentMethods" => $paymentMethods,
      "transactionTypes" => $transactionTypes
    ]);
  }

  public function setTemplateRenderer(TemplateRenderer $templateRenderer)
  {
    $this->templateRenderer = $templateRenderer;
  }
}