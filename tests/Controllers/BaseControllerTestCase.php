<?php

namespace Tests\Controllers;

use Tests\WebDriverTrait;
use Tests\BaseTestCase;

class BaseControllerTestCase extends BaseTestCase
{
  use WebDriverTrait;
  
  protected function setUp(): void
  {
    parent::setUp();
    $this->setUpWebDriver();
  }

  protected function tearDown(): void
  {
    $this->tearDownWebDriver();
    parent::tearDown();
  }
}