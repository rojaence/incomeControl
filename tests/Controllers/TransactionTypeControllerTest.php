<?php

use App\Controllers\TransactionTypeController;
use App\Services\TransactionTypeService;
use Tests\Controllers\BaseControllerTestCase;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class TransactionTypeControllerTest extends BaseControllerTestCase
{
  private $controller;
  private $service;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->controller = new TransactionTypeController();
    $this->service = new TransactionTypeService();
  }  

  public function testStoreTransactionType()
  {
    $this->browser->get(BASE_URL . "/transactiontypes/create");
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findELement(WebDriverBy::id('submit'))->click();
    $this->browser->get(BASE_URL . '/transactiontypes');
    $tableData = $this->browser->findElement(WebDriverBy::tagName('table'));
    $this->assertStringContainsString("Nombre de ejemplo", $tableData->getText());
  }

  public function testEmptyNameFormException()
  {
    $this->browser->get(BASE_URL . '/transactiontypes/create');
    $this->browser->findElement(WebDriverBy::id('submit'))->click();
    $currentUrl = $this->browser->getCurrentUrl();
    $alert = $this->browser->findElement(WebDriverBy::cssSelector('.alert'));
    $this->assertEquals(BASE_URL . '/transactiontypes/create', $currentUrl);
    $this->assertEquals('El nombre no puede estar vacío', $alert->getText());
  }

  public function testDuplicateNameFormException()
  {
    $this->browser->get(BASE_URL . '/transactiontypes/create');
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findELement(WebDriverBy::id('submit'))->click();

    $this->browser->get(BASE_URL . '/transactiontypes/create');
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findELement(WebDriverBy::id('submit'))->click();
    
    $alert = $this->browser->findElement(WebDriverBy::cssSelector('.alert'));
    $this->assertEquals("Ya existe un registro con el nombre 'Nombre de ejemplo'", $alert->getText());
  }

  public function testEditFormNoExceptions()
  {
    $this->browser->get(BASE_URL . "/transactiontypes/create");
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findElement(WebDriverBy::id('submit'))->click();
    
    // ESPERAR A QUE OCURRA LA REDIRECCIÓN LUEGO DE GUARDAR EL REGISTRO:  
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/transactiontypes')
    );
    $transactionTypes = $this->service->getAll();
    $sutId = $transactionTypes[0]->getId();

    $this->assertEquals(1, $sutId);

    $this->browser->get(BASE_URL . "/transactiontypes/edit/" . $sutId);
    $nameInput = $this->browser->findElement(WebDriverBy::name('name'));
    $nameInput->clear();
    $nameInput->sendKeys('Nombre editado');
    $this->browser->findElement(WebDriverBy::id('submit'))->click();

    // ESPERAR A QUE OCURRA LA REDIRECCIÓN LUEGO DE EDITAR EL REGISTRO:  
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/transactiontypes')
    );

    $sut = $this->service->getById($sutId);
    $tableData = $this->browser->findElement(WebDriverBy::tagName('table'));

    $this->assertEquals('Nombre editado', $sut->getName());
    $this->assertStringContainsString($sut->getName(), $tableData->getText());
  }

  public function testEditFormExceptions()
  {
    $this->browser->get(BASE_URL . "/transactiontypes/create");
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findElement(WebDriverBy::id('submit'))->click();
    
    // ESPERAR A QUE OCURRA LA REDIRECCIÓN LUEGO DE GUARDAR EL REGISTRO:  
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/transactiontypes')
    );

    $this->browser->get(BASE_URL . "/transactiontypes/create");
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo 2');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo 2');
    $this->browser->findElement(WebDriverBy::id('submit'))->click();
    
    // ESPERAR A QUE OCURRA LA REDIRECCIÓN LUEGO DE GUARDAR EL REGISTRO:  
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/transactiontypes')
    );

    $paymentMethods = $this->service->getAll();
    $sutId = $paymentMethods[0]->getId();

    // nombre vacio
    $this->browser->get(BASE_URL . "/transactiontypes/edit/" . $sutId);
    $nameInput = $this->browser->findElement(WebDriverBy::name('name'));
    $nameInput->clear();
    $this->browser->findElement(WebDriverBy::id('submit'))->click();

    $alert = $this->browser->findElement(WebDriverBy::className('alert'));
    $this->assertEquals('El nombre no puede estar vacío', $alert->getText());

    $this->browser->get(BASE_URL . '/transactiontypes');
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/transactiontypes')
    );

    $sut = $this->service->getById($sutId);
    $this->assertEquals('Nombre de ejemplo', $sut->getName());

    // nombre duplicado
    $this->browser->get(BASE_URL . "/transactiontypes/edit/" . $sutId);
    $nameInput = $this->browser->findElement(WebDriverBy::name('name'));
    $nameInput->clear();
    $nameInput->sendKeys('Nombre de ejemplo 2');
    $this->browser->findElement(WebDriverBy::id('submit'))->click();

    $alert = $this->browser->findElement(WebDriverBy::className('alert'));
    $this->assertEquals("Ya existe un registro con el nombre 'Nombre de ejemplo 2'", $alert->getText());

    $this->browser->get(BASE_URL . '/transactiontypes');
    $this->browser->wait()->until(
      WebDriverExpectedCondition::urlIs(BASE_URL . '/transactiontypes')
    );

    $sut = $this->service->getById($sutId);
    $this->assertEquals('Nombre de ejemplo', $sut->getName());
  }
}