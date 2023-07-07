<?php
use Tests\BaseTestCase;
use App\Services\PaymentMethodService;
use App\Models\PaymentMethodModel;

class PaymentMethodServiceTest extends BaseTestCase
{
  private $service;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->service = new PaymentMethodService();
  }
  
  public function testGetAllPaymentMethods()
  {
    $paymentMethods = $this->service->getAll(); 
    $this->assertEquals(0, count($paymentMethods));
  }

  public function testCreatePaymentMethod()
  {
    $pm1 = new PaymentMethodModel('Credit card', 'Description to credit card');
    $this->service->create($pm1);    
    $paymentMethods = $this->service->getAll(); 

    $this->assertEquals(1, count($paymentMethods));
    $this->assertEquals(1, $pm1->getId());
  }

  public function testUpdatePaymentMethod()
  {
    $pm1 = new PaymentMethodModel('Credit card', 'Description to credit card');
    $this->service->create($pm1);    
    $pm1->setDescription('Descripción actualizada');
    $this->service->update($pm1);    
    $paymentMethods = $this->service->getAll(); 

    $sut = $paymentMethods[0];

    $this->assertEquals('Descripción actualizada', $sut->getDescription());
  }

  public function testDeletePaymentMethod()
  {
    $pm1 = new PaymentMethodModel('Credit card', 'Description to credit card');
    $pm2 = new PaymentMethodModel('Cash', 'Description to cash');
    $pm3 = new PaymentMethodModel('Paypal', 'Description to Paypal');
    $this->service->create($pm1);    
    $this->service->create($pm2);    
    $this->service->create($pm3);    
    $this->service->delete(2);
    $paymentMethods = $this->service->getAll(); 

    $this->assertEquals(2, count($paymentMethods));
  }
}