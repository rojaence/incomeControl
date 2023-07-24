<?php

use App\Controllers\WithdrawalController;
use App\Services\WithdrawalService;

use Tests\Controllers\BaseDataControllerTestCase;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class WithdrawalControllerTest extends BaseDataControllerTestCase
{
  private $controller;
  private $withdrawalService;

  protected function setUp(): void
  {
    parent::setUp();
    $this->controller = new WithdrawalController();
    $this->withdrawalService = new WithdrawalService();
  }

  public function testCreateFormWithPaymentMethodsAndTransactionTypes()
  {
    $this->browser->get(BASE_URL . "/withdrawals/create");

    $firstPaymentMethods = array_slice($this->paymentMethods, 0, 5);
    $firstTransactionTypes = array_slice($this->transactionTypes, 0, 5);

    $paymentMethodSelect = $this->browser->findElement(WebDriverBy::name('payment_method_id'));
    $paymentMethodOptions = $paymentMethodSelect->findElements(WebDriverBy::tagName('option'));

    $transactionTypeSelect = $this->browser->findElement(WebDriverBy::name('transaction_type_id'));
    $transactionTypeOptions = $transactionTypeSelect->findElements(WebDriverBy::tagName('option'));

    foreach ($firstPaymentMethods as $index => $paymentMethod) {
      $expectedName = $paymentMethod->getName();
      $option = $paymentMethodOptions[$index]->getText();
      $this->assertEquals($expectedName, $option);
    }

    foreach ($firstTransactionTypes as $index => $transactionType) {
      $expectedName = $transactionType->getName();
      $option = $transactionTypeOptions[$index]->getText();
      $this->assertEquals($expectedName, $option);
    }
  }

  public function testStoreWithdrawal()
  {
    $this->browser->get(BASE_URL . "/withdrawals/create");
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    // ESPERAR A QUE OCURRA LA REDIRECCIÓN LUEGO DE GUARDAR EL REGISTRO:  
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals')
    );

    $tableData = $this->browser->findElement(WebDriverBy::tagName('table'));
    $this->assertStringContainsString("Descripción de ejemplo", $tableData->getText());
  }

  public function testStoreDescriptionException() {
    $this->browser->get(BASE_URL . "/withdrawals/create" );

    // Descripción vacía
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals/create')
    );
    
    $this->assertCount(0, $this->withdrawalService->getAll());
  }

  public function testStoreAmountException() 
  {
    // Amount <= 0
    $this->browser->get(BASE_URL . "/withdrawals/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(0);
    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();
    $this->assertCount(0, $this->withdrawalService->getAll());
  }

  public function testUpdate() 
  {
    $this->browser->get(BASE_URL . "/withdrawals/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals')
    );
    
    $this->browser->get(BASE_URL . '/withdrawals/edit/1');

    $descriptionInput = $this->browser->findElement(WebDriverBy::name('description'));
    $descriptionInput->clear();
    $descriptionInput->sendKeys('Another description');

    $amountInput = $this->browser->findElement(WebDriverBy::name('amount'));
    $amountInput->clear();
    $amountInput->sendKeys(200);

    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals')
    );
    
    $sut = $this->withdrawalService->getById(1);
    
    $this->assertEquals('Another description', $sut->getDescription());
    $this->assertEquals(200, $sut->getAmount());
  }

  public function testUpdateAmountException() 
  {
    $this->browser->get(BASE_URL . "/withdrawals/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals')
    );
    
    $this->browser->get(BASE_URL . '/withdrawals/edit/1');

    $amountInput = $this->browser->findElement(WebDriverBy::name('amount'));
    $amountInput->clear();

    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals/edit')
    );
    
    $sut = $this->withdrawalService->getById(1);
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));

    $this->assertEquals(20, $sut->getAmount());
    $this->assertEquals('El valor del monto no es válido', $alert->getText());
  }

  public function testUpdateAmountNegativeException() {

    $this->browser->get(BASE_URL . "/withdrawals/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals')
    );
    
    $this->browser->get(BASE_URL . '/withdrawals/edit/1');

    $amountInput = $this->browser->findElement(WebDriverBy::name('amount'));
    $amountInput->clear();
    $amountInput->sendKeys(-10);

    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals/edit')
    );
    
    $sut = $this->withdrawalService->getById(1);
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));

    $this->assertEquals(20, $sut->getAmount());
    $this->assertEquals('El monto debe ser mayor que cero', $alert->getText());
  }

  public function testUpdateDescriptionException() {
    $this->browser->get(BASE_URL . "/withdrawals/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals')
    );
    
    $this->browser->get(BASE_URL . '/withdrawals/edit/1');

    $descriptionInput = $this->browser->findElement(WebDriverBy::name('description'));
    $descriptionInput->clear();

    $this->browser->findElement(WebDriverBy::id('withdrawal-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/withdrawals/edit')
    );
    
    $sut = $this->withdrawalService->getById(1);
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));

    $this->assertEquals('Description', $sut->getDescription());
    $this->assertEquals('La descripción no puede estar vacía', $alert->getText());
  }
}