<?php

namespace Utils;

use League\Plates\Engine;

class TemplateRenderer
{
  private $templates;

  public function __construct(Engine $templates)
  {
    $this->templates = $templates;
  }

  public function render($templateName, $data = [])
  {
    return $this->templates->render($templateName, $data);
  }
}