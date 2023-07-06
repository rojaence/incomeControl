<?php

use App\Controllers\PaymentMethodController;
use PHPUnit\Framework\TestCase;
use App\Models\PaymentMethodModel;
use Tests\BaseTestCase;

class PaymentMethodControllerTest extends BaseTestCase
{
  private $controller;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->controller = new PaymentMethodController();
  }

  public function testStorePaymentMethod()
  {
    $pm1 = new PaymentMethodModel('Tarjeta de credito', 'Description de ejemplo');
    $this->controller->store($pm1);
    $this->assertEquals(1, $pm1->getId());
  }
}