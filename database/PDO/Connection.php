<?php

namespace Database\PDO;

class Connection 
{
  private static $instance;
  private $connection;

  private function __contruct()
  {
    $this->makeConnection();
  }

  public static function getInstance(): object
  {
    if(!self::$instance instanceof self)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function makeConnection(): void
  {
    try
    {
      $conn = new \PDO("mysql:host={$_ENV['HOST']};dbname={$_ENV['NAME']}", $_ENV["USER"], $_ENV["PASS"]);
      $setnames = $conn->prepare("SET NAMES 'utf8'");
      $setnames->execute();
      $this->connection = $conn;
    }
    catch(\PDOException $e)
    {
      die("Connection failed: {$e->getMessage()}");
    }
  }

  public function getConnection(): object
  {
    return $this->connection;
  }
}