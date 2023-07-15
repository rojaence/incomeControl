<?php

use App\Controllers\IncomeController;
use App\Services\IncomeService;
use Tests\Controllers\BaseDataControllerTestCase;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class IncomeControllerTest extends BaseDataControllerTestCase
{
  private $controller;
  private $incomeService;

  protected function setUp():void
  {
    parent::setUp();
    $this->controller = new IncomeController();
    $this->incomeService = new IncomeService();
  }

  public function testCreateFormWithPaymentMethodsAndTransactionTypes()
  {
    $this->browser->get(BASE_URL . "/incomes/create");

    $firstPaymentMethods = array_slice($this->paymentMethods, 0, 5);
    $firstTransactionTypes = array_slice($this->transactionTypes, 0, 5);

    $paymentMethodSelect = $this->browser->findElement(WebDriverBy::name('payment-method'));
    $paymentMethodOptions = $paymentMethodSelect->findElements(WebDriverBy::tagName('option'));

    $transactionTypeSelect = $this->browser->findElement(WebDriverBy::name('transaction-type'));
    $transactionTypeOptions = $transactionTypeSelect->findElements(WebDriverBy::tagName('option'));

    foreach ($paymentMethodOptions as $index => $optionElement) {
      $expectedName = $firstPaymentMethods[$index]->getName();
      $actualName = $optionElement->getText();
      $this->assertEquals($expectedName, $actualName);
    }

    foreach ($transactionTypeOptions as $index => $optionElement) {
      $expectedName = $firstTransactionTypes[$index]->getName();
      $actualName = $optionElement->getText();
      $this->assertEquals($expectedName, $actualName);
    }
  }
}