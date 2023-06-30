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
        /* if ($routeId && $routeId == "create") {
        } else if ($routeId && $routeId == "edit") {
        } else if ($routeId ) {

        } else {
        } */
        break;
      case HttpMethod::POST:
        $data = $model::fromArray($this->data);
        $resource->store($data);
        break;
      case HttpMethod::PUT:
        $data = $model::fromArray($this->data);
        $resource->update($data);
        break;
      case HttpMethod::DELETE:
        $resource->delete($routeId);
        break;
    }
  }
}