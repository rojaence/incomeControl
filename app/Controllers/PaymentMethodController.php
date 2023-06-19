<?php

namespace App\Controllers;

use App\Models\PaymentMethodModel;

class PaymentMethodController extends BaseController
{
  public function index()
  {

  }

  public function show()
  {

  }

  public function store($data)
  {
    if ($data instanceof PaymentMethodModel) {
      $this->dbConnection->query("INSERT INTO payment_method (name, description) VALUES (
          '{$data->getName()}',
          '{$data->getDescription()}'
        );");
    } else {
      throw new \Exception("El tipo de $data no es el correcto");
    }
  }
}