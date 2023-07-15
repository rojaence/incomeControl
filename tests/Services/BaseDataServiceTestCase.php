<?php
namespace Tests\Services;

use App\Services\PaymentMethodService;
use App\Services\TransactionTypeService;
use App\Models\PaymentMethodModel;
use App\Models\TransactionTypeModel;
use Tests\BaseTestCase;

class BaseDataServiceTestCase extends BaseTestCase
{
  protected $paymentMethodService;
  protected $transactionTypeService;
  protected $paymentMethods;
  protected $transactionTypes;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->paymentMethodService = new paymentMethodService();
    $this->transactionTypeService = new transactionTypeService();
    
    // Insertar varios datos para poder usar ids de métodos de pago y tipos de transacción

    $this->paymentMethodService->create(new PaymentMethodModel('Tarjeta de credito', 'Descripcion para tarjeta de credito'));
    $this->paymentMethodService->create(new PaymentMethodModel('Transferencia bancaria', 'Descripcion para transferencia bancaria'));
    $this->paymentMethodService->create(new PaymentMethodModel('Paypal', 'Descripcion para Paypal'));
    $this->paymentMethodService->create(new PaymentMethodModel('Cheque', 'Descripcion para cheque'));

    $this->transactionTypeService->create(new TransactionTypeModel('Compra', 'Descripción para compra'));
    $this->transactionTypeService->create(new TransactionTypeModel('Venta', 'Descripción para venta'));
    $this->transactionTypeService->create(new TransactionTypeModel('Reembolso', 'Descripción para reembolso'));
    $this->transactionTypeService->create(new TransactionTypeModel('Pago', 'Descripción para pago'));
    $this->transactionTypeService->create(new TransactionTypeModel('Deposito', 'Descripción para deposito'));
    $this->transactionTypeService->create(new TransactionTypeModel('Retiro', 'Descripción para retiro'));

    $this->paymentMethods = $this->paymentMethodService->getAll();
    $this->transactionTypes = $this->transactionTypeService->getAll();
  }
}
