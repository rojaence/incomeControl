<?php

namespace App\Models;

class PaymentMethodModel
{
  protected ?int $id;
  protected string $name;
  protected string $description;

  public function __construct(string $name, ?string $description, ?int $id = null)
  {
    $this->name = $name;
    $this->description = $description ?? '';
    $this->id = $id;
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
        return new self($name, $description, $id);
    } else {
        throw new \InvalidArgumentException('El array no contiene los atributos requeridos.');
    }
  }
}