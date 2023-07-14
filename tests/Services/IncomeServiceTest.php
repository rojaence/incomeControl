<?php

use App\Exceptions\DataTypeException;
use App\Services\IncomeService;
use App\Services\PaymentMethodService;
use App\Services\TransactionTypeService;
use App\Models\IncomeModel;
use App\Models\PaymentMethodModel;
use App\Models\TransactionTypeModel;
use Tests\BaseTestCase;

class IncomeServiceTest extends BaseTestCase
{
  private $incomeService;
  private $paymentMethodService;
  private $transactionTypeService;
  private $paymentMethods;
  private $transactionTypes;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->incomeService = new IncomeService();
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
  
  public function testCreateIncomeNoExceptions()
  {
    $this->incomeService->create(new IncomeModel(50, 'Ingreso de ejemplo 1', 1, 1));
    $this->incomeService->create(new IncomeModel(100, 'Ingreso de ejemplo 2', 1, 2));
    $this->incomeService->create(new IncomeModel(300, 'Ingreso de ejemplo 3', 2, 4));

    $this->assertCount(3, $this->incomeService->getAll());
  }

  public function testCreateIncomeWithExceptions() 
  {
    // Test para validar las excepciones del método create
    // Monto <= 0
    try {
      $this->incomeService->create(new IncomeModel(0, 'Ingreso de ejemplo 1', 1, 1));
      $this->fail('Se esperaba una excepción InvalidArgumentException por monto <= 0');
    } catch (InvalidArgumentException $e) {}

    try {
      $this->incomeService->create(new IncomeModel(-100, 'Ingreso de ejemplo 2', 1, 1));  
      $this->fail('Se esperaba una excepción InvalidArgumentException por monto <= 0');
    } catch (InvalidArgumentException $e) {}

    // Description vacia
    try {
      $this->incomeService->create(new IncomeModel(100, '', 1, 1));  
      $this->fail('Se esperaba una excepción InvalidArgumentException por descripción vacía');
    } catch (InvalidArgumentException $e) {}

    try {
      $this->incomeService->create(new IncomeModel(100, '  ', 1, 1));
      $this->fail('Se esperaba una excepción InvalidArgumentException por descripción vacía');
    } catch (InvalidArgumentException $e) {}


    // Tipo Transacción o Método pago no establecido
    
    try {
      $this->incomeService->create(new IncomeModel(100, 'Ejemplo', 1, 'adsfadsf'));
      $this->fail('Se esperaba una excepción InvalidArgumentException por FK no válido');
    } catch (InvalidArgumentException $e) {}
    catch (\TypeError $e) {}

    try {
      $this->incomeService->create(new IncomeModel(100, 'Ejemplo', 'adsfadfad', 1));
      $this->fail('Se esperaba una excepción InvalidArgumentException por FK no válido');
    } catch (InvalidArgumentException $e) {}
    catch(\TypeError $e) {}

    try {
      $this->incomeService->create(new IncomeModel(100, 'Ejemplo', ' ', 'adsfadsf'));
      $this->fail('Se esperaba una excepción InvalidArgumentException por FK no válido');
    } catch (InvalidArgumentException $e) {}
    catch(\TypeError $e) {}

    try {
      $this->incomeService->create(new IncomeModel(50, 'Ingreso de ejemplo 1', 10, 10));
    } catch(\PDOException $e) {}
    $this->incomeService->create(new IncomeModel(50, 'Ingreso de ejemplo 1', 1, 1));

    $this->assertCount(1, $this->incomeService->getAll());
  }

  public function testUpdateIncomeNoExceptions()
  {

    $sut1 = new IncomeModel(50, 'Ingreso de ejemplo 1', 1, 1);
    $sut2 = new IncomeModel(100, 'Ingreso de ejemplo 2', 1, 2);
    $this->incomeService->create($sut1);
    $this->incomeService->create($sut2);

    $sut1->setAmount(200);
    $sut2->setDescription('Ingreso de ejemplo 2 editado');
    $this->incomeService->update($sut1);
    $this->incomeService->update($sut2);


    $sut1 = $this->incomeService->getById($sut1->getId());
    $sut2 = $this->incomeService->getById($sut2->getId());
    $this->assertCount(2, $this->incomeService->getAll());
    $this->assertEquals(200, $sut1->getAmount());
    $this->assertEquals('Ingreso de ejemplo 2 editado', $sut2->getDescription());
  }

  public function testUpdateIncomeWithExceptions()
  {
    $sut1 = new IncomeModel(50, 'Ingreso de ejemplo 1', 1, 1);
    $sut2 = new IncomeModel(100, 'Ingreso de ejemplo 2', 1, 2);
    $this->incomeService->create($sut1);
    $this->incomeService->create($sut2);

    try {
      $sut1->setAmount(-10);
      $this->incomeService->update($sut1);
      $this->fail('Excepción encontrada por monto <= 0');
    } catch (InvalidArgumentException $e) {}

    try {
      $sut2->setDescription('  ');
      $this->incomeService->update($sut2);
      $this->fail('Excepción encontrada por descripción vacía');
    } catch (InvalidArgumentException $e) {}
    finally { $sut2->setDescription('Ingreso de ejemplo 1'); }

    try {
      $sut2->setTransactionTypeId(20);
      $this->incomeService->update($sut2);
      $this->fail('Excepción encontrada por clave foránea no existente');
    } catch (\PDOException $e) {}

    $sut1 = $this->incomeService->getById($sut1->getId());
    $sut2 = $this->incomeService->getById($sut2->getId());
    $this->assertCount(2, $this->incomeService->getAll());
    $this->assertEquals(50, $sut1->getAmount());
    $this->assertEquals('Ingreso de ejemplo 2', $sut2->getDescription());
    $this->assertEquals(2, $sut2->getTransactionTypeId());
  }

  public function testGetAllIncomes()
  {
    $incomes = $this->incomeService->getAll(); 

    $this->assertEquals(0, count($incomes));
    $this->assertEquals(4, count($this->paymentMethods));
    $this->assertEquals(6, count($this->transactionTypes));
  }

  public function testDeleteIncome()
  {
    $sut1 = new IncomeModel(50, 'Ingreso de ejemplo 1', 1, 1);
    $sut2 = new IncomeModel(100, 'Ingreso de ejemplo 2', 1, 2);
    $sut3 = new IncomeModel(100, 'Ingreso de ejemplo 3', 2, 3);
    $this->incomeService->create($sut1);
    $this->incomeService->create($sut2);
    $this->incomeService->create($sut3);

    $this->incomeService->delete($sut1->getId());
    // Este id no existe
    $this->incomeService->delete(4);
    $this->assertCount(2, $this->incomeService->getAll());
  }
}