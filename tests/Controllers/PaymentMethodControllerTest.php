<?php

use App\Controllers\PaymentMethodController;
use App\Models\PaymentMethodModel;
use Tests\BaseControllerTestCase;

use Facebook\WebDriver\WebDriverBy;

class PaymentMethodControllerTest extends BaseControllerTestCase
{
  private $controller;

  protected function setUp(): void
  {
    parent::setUp();    
    $this->controller = new PaymentMethodController();
  }

  public function testCanAccessBrowser()
  {
    $this->browser->get(BASE_URL . "/");
    $title = $this->browser->findElement(WebDriverBy::tagName('h1'));
    $subtitle = $this->browser->findElement(WebDriverBy::tagName('h2'));
    $this->assertEquals('Inicio', $title->getText());
    $this->assertEquals('Bienvenido a mi página de inicio', $subtitle->getText());
  }
  
  public function testStorePaymentMethod()
  {
    $this->browser->get(BASE_URL . "/paymentmethods/create");
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findELement(WebDriverBy::id('submit'))->click();
    $tableData = $this->browser->findElement(WebDriverBy::tagName('table'));
    $this->assertStringContainsString("Nombre de ejemplo", $tableData->getText());
  }

  public function testEmptyNameFormException()
  {
    $this->browser->get(BASE_URL . '/paymentmethods/create');
    $this->browser->findElement(WebDriverBy::id('submit'))->click();
    $currentUrl = $this->browser->getCurrentUrl();
    $alert = $this->browser->findElement(WebDriverBy::cssSelector('.alert'));
    $this->assertEquals(BASE_URL . '/paymentmethods/create', $currentUrl);
    $this->assertEquals('El nombre no puede estar vacío', $alert->getText());
  }

  public function testDuplicateNameFormException()
  {
    $this->browser->get(BASE_URL . '/paymentmethods/create');
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findELement(WebDriverBy::id('submit'))->click();

    $this->browser->get(BASE_URL . '/paymentmethods/create');
    $this->browser->findElement(WebDriverBy::name('name'))->sendKeys('Nombre de ejemplo');
    $this->browser->findElement(WebDriverBy::name('description'))->sendKeys('Descripción de ejemplo');
    $this->browser->findELement(WebDriverBy::id('submit'))->click();
    
    $alert = $this->browser->findElement(WebDriverBy::cssSelector('.alert'));
    $this->assertEquals('Ya existe un registro con el nombre proporcionado', $alert->getText());
  }

  public function testEditForm()
  {
    
  }
}