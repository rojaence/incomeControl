<?php

namespace App\Models;

class PaymentMethodModel
{
  protected ?int $id;
  protected string $name;
  protected string $description;
  protected bool $state;

  public function __construct(string $name, ?string $description, ?int $id = null, bool $state = true)
  {
    $this->name = $name;
    $this->description = $description ?? '';
    $this->id = $id;
    $this->state = $state;
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
  public function setDescription(string $description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get the value of name
   */ 
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */ 
  public function setName(string $name)
  {
    $this->name = $name;

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

  public static function fromArray(array $data) {
    if (isset($data['name'])) {
      $name = $data['name'];
      $description = $data['description'] ?? '';
      $id = $data['id'] ?? null;
      // $state = isset($data['state']) && $data['state'] === 'on';
      $stateValues = ['on', true, 1, '1'];
      $state = isset($data['state']) && in_array($data['state'], $stateValues);
      return new self($name, $description, $id, $state);
    } else {
        throw new \InvalidArgumentException('El array no contiene los atributos requeridos.');
    }
  }

  /**
   * Get the value of state
   */ 
  public function getState()
  {
    return $this->state;
  }

  /**
   * Set the value of state
   *
   * @return  self
   */ 
  public function setState(bool $state)
  {
    $this->state = $state;

    return $this;
  }
}
