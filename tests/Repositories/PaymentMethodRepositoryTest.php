<?php
use Tests\BaseTestCase;
use App\Repositories\PaymentMethodRepository;

class PaymentMethodRepositoryTest extends BaseTestCase
{
  private $repository;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->repository = new PaymentMethodRepository();
  }
  
  public function testGetAllPaymentMethods()
  {
    $paymentMethods = $this->repository->getAll(); 
    $this->assertEquals(0, count($paymentMethods));
  }
}