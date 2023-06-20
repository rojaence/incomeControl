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
      $query = $this->dbConnection->prepare("INSERT INTO payment_method (name, description) VALUES (?, ?);");
      $name = $data->getName();
      $description = $data->getDescription();
      $query->bind_param("ss", $name, $description);
      $query->execute();
    } else {
      throw new \Exception("El tipo de $data no es el correcto");
    }
  }
}