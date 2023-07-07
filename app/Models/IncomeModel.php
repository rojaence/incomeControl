<?php

namespace App\Models;

use App\Services\PaymentMethodService;
use App\Services\TransactionTypeService;

class IncomeModel
{
  private ?int $id;
  private string $date;
  private float $amount;
  private string $description;
  private int $paymentMethodId;
  private int $transactionTypeId;


  public function __construct(float $amount, ?string $description, int $paymentMethodId, int $transactionTypeId, string $date = null, ?int $id = null)
  {
    $this->id = $id ?? null;
    $this->date = $date ?? date("Y-m-d\TH:i:s");
    $this->amount = $amount;
    $this->description = $description ?? '';
    $this->paymentMethodId = $paymentMethodId;
    $this->transactionTypeId = $transactionTypeId;
  }

  /**
   * Get the value of transactionTypeId
   */ 
  public function getTransactionTypeId()
  {
    return $this->transactionTypeId;
  }

  /**
   * Set the value of transactionTypeId
   *
   * @return  self
   */ 
  public function setTransactionTypeId($transactionTypeId)
  {
    $this->transactionTypeId = $transactionTypeId;

    return $this;
  }

  /**
   * Get the value of paymentMethodId
   */ 
  public function getPaymentMethodId()
  {
    return $this->paymentMethodId;
  }

  /**
   * Set the value of paymentMethodId
   *
   * @return  self
   */ 
  public function setPaymentMethodId($paymentMethodId)
  {
    $this->paymentMethodId = $paymentMethodId;

    return $this;
  }

  /**
   * Get the value of description
   */ 
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set the value of description
   *
   * @return  self
   */ 
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get the value of amount
   */ 
  public function getAmount()
  {
    return $this->amount;
  }

  /**
   * Set the value of amount
   *
   * @return  self
   */ 
  public function setAmount($amount)
  {
    $this->amount = $amount;

    return $this;
  }

  /**
   * Get the value of date
   */ 
  public function getDate()
  {
    return $this->date;
  }

  /**
   * Set the value of date
   *
   * @return  self
   */ 
  public function setDate($date)
  {
    $this->date = $date;

    return $this;
  }

  /**
   * Get the value of id
   */ 
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */ 
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  public static function fromArray(array $data)
  {
    if (isset($data['amount'], $data['description'], $data['payment_method_id'], $data['transaction_type_id']))
    {
      $amount = $data['amount'];
      $description = $data['description'] ?? '';
      $paymentMethodId = $data['payment_method_id'];
      $transactionTypeId = $data['transaction_type_id'];
      $date = $data['date'] ?? date("Y-m-d\TH:i:s");
      $id = $data['id'] ?? null;
      return new self($amount, $description, $paymentMethodId, $transactionTypeId, $date, $id);
    } else {
      throw new \InvalidArgumentException("El array no contiene los atributos requeridos");
    }
  }

  public function getPaymentMethodName()
  {
    $service = new PaymentMethodService();
    return $service->getPaymentMethodNameById($this->paymentMethodId);
  }

  public function getTransactionTypeName()
  {
    $service = new TransactionTypeService();
    return $service->getTransactionTypeNameById($this->transactionTypeId);
  }
}