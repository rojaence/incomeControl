<?php

namespace Router;

use Constants\HttpMethod;

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

  public function route($controller, $id)
  {

    $resource = new $controller(); 

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