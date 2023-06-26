<?php

namespace Router;

use Constants\HttpMethod;
use Utils\TemplateRenderer;

class RouterHandler
{
  protected $method;
  protected $data;

  public function setMethod(string $method)
  {
    $this->method = $method;
  }

  public function setData(array $data)
  {
    $this->data = $data;
  }

  public function route($controller, TemplateRenderer $templates, $id)
  {

    $resource = new $controller(); 
    $resource->setTemplateRenderer($templates);

    switch ($this->method) {
      case HttpMethod::GET:
        if ($id && $id == "create") {
          $resource->create();
        } else if ($id) {
          $resource->show($id);
        } else {
          $resource->index();
        }
        break;
      case HttpMethod::POST:
        $resource->store($this->data);
        break;
      case HttpMethod::DELETE:
        $resource->delete($id);
        break;
    }
  }
}