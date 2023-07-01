<?php

namespace Utils;
use Constants\ToastType;

trait Toast
{
  private string $message;
  private string $type;

  public function __construct(string $message, string $type = ToastType::INFO)
  {
    $this->message = $message;
    $this->type = $type;
  }

  public function getMessage(): string
  {
    return $this->message;
  }

  public function getType(): string
  {
    return $this->type;
  }
}