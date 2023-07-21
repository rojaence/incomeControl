<?php

use App\Exceptions\DataTypeException;
use App\Exceptions\InvalidNameException;
use Tests\BaseTestCase;
use App\Services\TransactionTypeService;
use App\Models\TransactionTypeModel;

class TransactionTypeServiceTest extends BaseTestCase
{
  private $service;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->service = new TransactionTypeService();
  }
  
  public function testGetAllTransactionTypes()
  {
    $transactionTypes = $this->service->getAll(); 
    $this->assertEquals(0, count($transactionTypes));
  }

  public function testCreateTransactionType()
  {
    $tt1 = new TransactionTypeModel('Compra', 'Description to credit card');
    $this->service->create($tt1);    
    $transactionTypes = $this->service->getAll(); 

    $this->assertEquals(1, count($transactionTypes));
    $this->assertEquals(1, $tt1->getId());
  }

  public function testUpdateTransactionType()
  {
    $tt1 = new TransactionTypeModel('Compra', 'Description to credit card');
    $this->service->create($tt1);    
    $tt1->setDescription('Descripción actualizada');
    $this->service->update($tt1);    
    $transactionTypes = $this->service->getAll(); 

    $sut = $transactionTypes[0];

    $this->assertEquals('Descripción actualizada', $sut->getDescription());
  }

  public function testDeleteTransactionType()
  {
    $tt1 = new TransactionTypeModel('Compra', 'Description to credit card');
    $tt2 = new TransactionTypeModel('Retiro', 'Description to cash');
    $tt3 = new TransactionTypeModel('Reembolso', 'Description to Reembolso');
    $this->service->create($tt1);    
    $this->service->create($tt2);    
    $this->service->create($tt3);    
    $this->service->delete(2);
    $transactionTypes = $this->service->getAll(); 

    $this->assertEquals(2, count($transactionTypes));
  }

  public function testIsDuplicateOrEmptyName()
  {
    $tt1 = new TransactionTypeModel('Compra', 'Description to credit card');
    $tt2 = new TransactionTypeModel('Retiro', 'Description to cash');
    $tt3 = new TransactionTypeModel('Reembolso', 'Description to Reembolso');
    $tt4 = new TransactionTypeModel('Reembolso', 'Description to Reembolso');
    $tt5 = new TransactionTypeModel('', 'Description');
    $tt6 = ['Name' => 'Data type not permitted'];
    $this->service->create($tt1);    
    $this->service->create($tt2);    
    $this->service->create($tt3);    

    $this->expectException(InvalidNameException::class);
    $this->service->create($tt4);    

    $this->expectException(InvalidNameException::class);
    $this->service->create($tt5);    

    $this->expectException(DataTypeException::class);
    $this->service->create($tt6);    

    $transactionTypes = $this->service->getAll(); 
    $this->assertEquals(3, count($transactionTypes));
  }
}