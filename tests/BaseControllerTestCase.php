<?php

namespace Tests;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class BaseControllerTestCase extends BaseTestCase
{
  protected $serverUrl;
  protected $browser;
  protected $appUrl;

  protected function setUp(): void
  {
    parent::setUp();
    if (!defined('BASE_URL')) {
      define('BASE_URL', 'http://incomecontrol.test');
    }
    $this->serverUrl = "http://localhost:4444";
    $this->appUrl = BASE_URL;
    $this->browser = RemoteWebDriver::create($this->serverUrl);
  }

  protected function tearDown(): void
  {
    $this->browser->quit();
    parent::tearDown();
  }
}