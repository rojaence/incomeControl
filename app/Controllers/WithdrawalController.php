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

  public function destroy($id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM withdrawal WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function update($data)
  {
    if ($data instanceof WithdrawalModel)
    {
      $query = $this->dbConnection->prepare("UPDATE withdrawal SET payment_method_id = :payment_method_id, transaction_type_id = :transaction_type_id, date = :date, amount = :amount, description = :description WHERE id = :id");
      $paymentMethodId = $data->getPaymentMethodId();
      $transactionTypeId = $data->getTransactionTypeId();
      $date = $data->getDate();
      $amount = $data->getAmount();
      $description = $data->getDescription();
      $id = $data->getId();
      $query->bindValue(':payment_method_id', $paymentMethodId, \PDO::PARAM_INT);
      $query->bindValue(':transaction_type_id', $transactionTypeId, \PDO::PARAM_INT);
      $query->bindValue(':date', $date, \PDO::PARAM_STR);
      $query->bindValue(':amount', $amount, \PDO::PARAM_STR);
      $query->bindValue(':description', $description, \PDO::PARAM_STR);
      $query->bindValue(':id', $id, \PDO::PARAM_INT);
      $query->execute();
    }
    else 
    {
      throw new \InvalidArgumentException("El tipo de data debe ser una instancia de IncomeModel");
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