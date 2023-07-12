<?php

use Tests\BaseControllerTestCase;
use Facebook\WebDriver\WebDriverBy;

class WebDriverTest extends BaseControllerTestCase
{
  public function testCanAccessBrowser()
  {
    $this->browser->get(BASE_URL . "/");
    $title = $this->browser->findElement(WebDriverBy::tagName('h1'));
    $subtitle = $this->browser->findElement(WebDriverBy::tagName('h2'));
    $this->assertEquals('Inicio', $title->getText());
    $this->assertEquals('Bienvenido a mi página de inicio', $subtitle->getText());
  }
}
