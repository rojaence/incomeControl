<?php

namespace Tests;

use Facebook\WebDriver\Remote\RemoteWebDriver;

trait WebDriverTrait
{
  protected $serverUrl;
  protected $browser;
  protected $appUrl;

  protected function setUpWebDriver(): void
  {
    if (!defined('BASE_URL')) {
      define('BASE_URL', 'http://incomecontrol.test');
    }
    $this->serverUrl = "http://localhost:4444";
    $this->appUrl = BASE_URL;
    $this->browser = RemoteWebDriver::create($this->serverUrl);
  }

  protected function tearDownWebDriver(): void
  {
    $this->browser->quit();
  }
}
