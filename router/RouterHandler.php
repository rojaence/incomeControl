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
    $this->method = strtoupper($method);
  }

  public function setData(array $data)
  {
    $this->data = $data;
  }

  public function route($controller, TemplateRenderer $templates, $routeId, $model = null, $dataId = null)
  {

    $resource = new $controller(); 
    $resource->setTemplateRenderer($templates);

    switch ($this->method) {
      case HttpMethod::GET:
        if ($routeId) {
          if ($routeId == "create") {
            $resource->create();
          } else if ($routeId == "edit") {
            $resource->edit($dataId);
          } else {
            $resource->show($routeId);
          }
        } else {
          $resource->index();
        }
        break;
      case HttpMethod::POST:
        $resource->store($this->data);
        break;
      case HttpMethod::PUT:
        $resource->update($this->data);
        break;
      case HttpMethod::DELETE:
        $resource->destroy($dataId);
        break;
    }
  }
}