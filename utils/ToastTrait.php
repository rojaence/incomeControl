<?php

namespace Utils;
use Constants\ToastType;

trait ToastTrait
{
  public function setToast(string $message, string $type = ToastType::INFO) 
  {
    $_SESSION['toast'] = ['message' => $message, 'type' => $type];
  }

  public function getToast()
  {
    $toast = $_SESSION['toast'] ?? null;
    unset($_SESSION['toast']);
    return $toast;
  }

  public function clearToast()
  {
    unset($_SESSION['toast']);
  }
}