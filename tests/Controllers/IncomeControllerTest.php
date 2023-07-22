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

  public function testStoreIncome()
  {
    $this->browser->get(BASE_URL . "/incomes/create");
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    // ESPERAR A QUE OCURRA LA REDIRECCIÓN LUEGO DE GUARDAR EL REGISTRO:  
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes')
    );

    $tableData = $this->browser->findElement(WebDriverBy::tagName('table'));
    $this->assertStringContainsString("Descripción de ejemplo", $tableData->getText());
  }

  public function testStoreDescriptionException() {
    $this->browser->get(BASE_URL . "/incomes/create" );

    // Descripción vacía
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes/create')
    );

    $alert = $this->browser->findElement(WebDriverBy::className('alert'));
    $this->assertEquals("La descripción no puede estar vacía", $alert->getText());
    $this->assertCount(0, $this->incomeService->getAll());
  }

  public function testStoreAmountException() 
  {
    // Amount <= 0
    $this->browser->get(BASE_URL . "/incomes/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(0);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();
    
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes/create')
    );
    
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));
    $this->assertEquals("El monto debe ser mayor que cero", $alert->getText());
    $this->assertCount(0, $this->incomeService->getAll());
  }

  public function testUpdate() 
  {
    $this->browser->get(BASE_URL . "/incomes/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes')
    );
    
    $this->browser->get(BASE_URL . '/incomes/edit/1');

    $descriptionInput = $this->browser->findElement(WebDriverBy::name('description'));
    $descriptionInput->clear();
    $descriptionInput->sendKeys('Another description');

    $amountInput = $this->browser->findElement(WebDriverBy::name('amount'));
    $amountInput->clear();
    $amountInput->sendKeys(200);

    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes')
    );
    
    $sut = $this->incomeService->getById(1);
    
    $this->assertEquals('Another description', $sut->getDescription());
    $this->assertEquals(200, $sut->getAmount());
  }

  // TODO: Implement tests to Form Exceptions and Delete Controller function
  public function testUpdateAmountException() 
  {
    $this->browser->get(BASE_URL . "/incomes/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes')
    );
    
    $this->browser->get(BASE_URL . '/incomes/edit/1');

    $amountInput = $this->browser->findElement(WebDriverBy::name('amount'));
    $amountInput->clear();

    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes/edit')
    );
    
    $sut = $this->incomeService->getById(1);
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));

    $this->assertEquals(20, $sut->getAmount());
    $this->assertEquals('El valor del monto no es válido', $alert->getText());
  }

  public function testUpdateInvalidAmountException() 
  {
    $this->browser->get(BASE_URL . "/incomes/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes')
    );
    
    $this->browser->get(BASE_URL . '/incomes/edit/1');

    $amountInput = $this->browser->findElement(WebDriverBy::name('amount'));
    $amountInput->clear();

    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes/edit')
    );
    
    $sut = $this->incomeService->getById(1);
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));

    $this->assertEquals(20, $sut->getAmount());
    $this->assertEquals('El valor del monto no es válido', $alert->getText());
  }

  public function testUpdateAmountNegativeException() {

    $this->browser->get(BASE_URL . "/incomes/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes')
    );
    
    $this->browser->get(BASE_URL . '/incomes/edit/1');

    $amountInput = $this->browser->findElement(WebDriverBy::name('amount'));
    $amountInput->clear();
    $amountInput->sendKeys(-10);

    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes/edit')
    );
    
    $sut = $this->incomeService->getById(1);
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));

    $this->assertEquals(20, $sut->getAmount());
    $this->assertEquals('El monto debe ser mayor que cero', $alert->getText());
  }

  public function testUpdateDescriptionException() {
    $this->browser->get(BASE_URL . "/incomes/create" );
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Description');
    $this->browser->findElement(WebDriverBy::name('amount'))->sendKeys(20);
    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes')
    );
    
    $this->browser->get(BASE_URL . '/incomes/edit/1');

    $descriptionInput = $this->browser->findElement(WebDriverBy::name('description'));
    $descriptionInput->clear();

    $this->browser->findElement(WebDriverBy::id('income-form'))->submit();

    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/incomes/edit')
    );
    
    $sut = $this->incomeService->getById(1);
    $alert = $this->browser->findElement(WebDriverBy::className('alert'));

    $this->assertEquals('Description', $sut->getDescription());
    $this->assertEquals('La descripción no puede estar vacía', $alert->getText());
  }
}



