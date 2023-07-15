<?php

namespace Tests\Controllers;

use Tests\WebDriverTrait;
use Tests\Services\BaseDataServiceTestCase;

class BaseDataControllerTestCase extends BaseDataServiceTestCase
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