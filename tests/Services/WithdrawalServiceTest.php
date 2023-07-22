<?php

use App\Services\WithdrawalService;
use App\Models\WithdrawalModel;
use Tests\Services\BaseDataServiceTestCase;

class WithdrawalServiceTest extends BaseDataServiceTestCase
{
  private $withdrawalService;

  protected function setUp(): void
  {
    parent::setUp();
    $this->withdrawalService = new WithdrawalService();
  }

  public function testCreateWithdrawalNoExceptions()
  {    
    $this->withdrawalService->create(new WithdrawalModel(50, 'Ingreso de ejemplo 1', 1, 1));
    $this->withdrawalService->create(new WithdrawalModel(100, 'Ingreso de ejemplo 2', 1, 2));
    $this->withdrawalService->create(new WithdrawalModel(300, 'Ingreso de ejemplo 3', 2, 4));

    $this->assertCount(3, $this->withdrawalService->getAll());
  }

  public function testCreateIncomeWithExceptions() 
  {
    // Test para validar las excepciones del método create
    // Monto <= 0
    try {
      $this->withdrawalService->create(new WithdrawalModel(0, 'Ingreso de ejemplo 1', 1, 1));
      $this->fail('Se esperaba una excepción InvalidArgumentException por monto <= 0');
    } catch (InvalidArgumentException $e) {}

    try {
      $this->withdrawalService->create(new WithdrawalModel(-100, 'Ingreso de ejemplo 2', 1, 1));  
      $this->fail('Se esperaba una excepción InvalidArgumentException por monto <= 0');
    } catch (InvalidArgumentException $e) {}

    // Description vacia
    try {
      $this->withdrawalService->create(new WithdrawalModel(100, '', 1, 1));  
      $this->fail('Se esperaba una excepción InvalidArgumentException por descripción vacía');
    } catch (InvalidArgumentException $e) {}

    try {
      $this->withdrawalService->create(new WithdrawalModel(100, '  ', 1, 1));
      $this->fail('Se esperaba una excepción InvalidArgumentException por descripción vacía');
    } catch (InvalidArgumentException $e) {}


    // Tipo Transacción o Método pago no establecido
    
    try {
      $this->withdrawalService->create(new WithdrawalModel(100, 'Ejemplo', 1, 'adsfadsf'));
      $this->fail('Se esperaba una excepción InvalidArgumentException por FK no válido');
    } catch (InvalidArgumentException $e) {}
    catch (\TypeError $e) {}

    try {
      $this->withdrawalService->create(new WithdrawalModel(100, 'Ejemplo', 'adsfadfad', 1));
      $this->fail('Se esperaba una excepción InvalidArgumentException por FK no válido');
    } catch (InvalidArgumentException $e) {}
    catch(\TypeError $e) {}

    try {
      $this->withdrawalService->create(new WithdrawalModel(100, 'Ejemplo', ' ', 'adsfadsf'));
      $this->fail('Se esperaba una excepción InvalidArgumentException por FK no válido');
    } catch (InvalidArgumentException $e) {}
    catch(\TypeError $e) {}

    try {
      $this->withdrawalService->create(new WithdrawalModel(50, 'Ingreso de ejemplo 1', 10, 10));
    } catch(\PDOException $e) {}
    $this->withdrawalService->create(new WithdrawalModel(50, 'Ingreso de ejemplo 1', 1, 1));

    $this->assertCount(1, $this->withdrawalService->getAll());
  }

  public function testUpdateWithdrawalNoExceptions()
  {

    $sut1 = new WithdrawalModel(50, 'Ingreso de ejemplo 1', 1, 1);
    $sut2 = new WithdrawalModel(100, 'Ingreso de ejemplo 2', 1, 2);
    $this->withdrawalService->create($sut1);
    $this->withdrawalService->create($sut2);

    $sut1->setAmount(200);
    $sut2->setDescription('Ingreso de ejemplo 2 editado');
    $this->withdrawalService->update($sut1);
    $this->withdrawalService->update($sut2);


    $sut1 = $this->withdrawalService->getById($sut1->getId());
    $sut2 = $this->withdrawalService->getById($sut2->getId());
    $this->assertCount(2, $this->withdrawalService->getAll());
    $this->assertEquals(200, $sut1->getAmount());
    $this->assertEquals('Ingreso de ejemplo 2 editado', $sut2->getDescription());
  }

  public function testUpdateWithdrawalWithExceptions()
  {
    $sut1 = new WithdrawalModel(50, 'Ingreso de ejemplo 1', 1, 1);
    $sut2 = new WithdrawalModel(100, 'Ingreso de ejemplo 2', 1, 2);
    $this->withdrawalService->create($sut1);
    $this->withdrawalService->create($sut2);

    try {
      $sut1->setAmount(-10);
      $this->withdrawalService->update($sut1);
      $this->fail('Excepción encontrada por monto <= 0');
    } catch (InvalidArgumentException $e) {}

    try {
      $sut2->setDescription('  ');
      $this->withdrawalService->update($sut2);
      $this->fail('Excepción encontrada por descripción vacía');
    } catch (InvalidArgumentException $e) {}
    finally { $sut2->setDescription('Ingreso de ejemplo 1'); }

    try {
      $sut2->setTransactionTypeId(20);
      $this->withdrawalService->update($sut2);
      $this->fail('Excepción encontrada por clave foránea no existente');
    } catch (\PDOException $e) {}

    $sut1 = $this->withdrawalService->getById($sut1->getId());
    $sut2 = $this->withdrawalService->getById($sut2->getId());
    $this->assertCount(2, $this->withdrawalService->getAll());
    $this->assertEquals(50, $sut1->getAmount());
    $this->assertEquals('Ingreso de ejemplo 2', $sut2->getDescription());
    $this->assertEquals(2, $sut2->getTransactionTypeId());
  }


  public function testGetAllWithdrawals()
  {
    $withdrawals = $this->withdrawalService->getAll(); 

    $this->assertEquals(0, count($withdrawals));
    $this->assertEquals(4, count($this->paymentMethods));
    $this->assertEquals(6, count($this->transactionTypes));
  }

  public function testDeleteWithdrawal()
  {
    $sut1 = new WithdrawalModel(50, 'Ingreso de ejemplo 1', 1, 1);
    $sut2 = new WithdrawalModel(100, 'Ingreso de ejemplo 2', 1, 2);
    $sut3 = new WithdrawalModel(100, 'Ingreso de ejemplo 3', 2, 3);
    $this->withdrawalService->create($sut1);
    $this->withdrawalService->create($sut2);
    $this->withdrawalService->create($sut3);

    $this->withdrawalService->delete($sut1->getId());
    // Este id no existe
    $this->withdrawalService->delete(4);
    $this->assertCount(2, $this->withdrawalService->getAll());
  }
}