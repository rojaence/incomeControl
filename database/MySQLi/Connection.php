<?php

namespace Database\MySQLi;

class Connection
{
  private static $instance;
  private $connection;

  private function __construct()
  {
    $this->makeConnection();
  }

  public static function getInstance(): object
  {
    if (!self::$instance instanceof self)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function makeConnection(): void
  {
    try
    {
      $conn = new \mysqli(
        $_ENV["DB_HOST"], 
        $_ENV["DB_USER"], 
        $_ENV["DB_PASS"],
        $_ENV["DB_NAME"]);
      $setnames = $conn->prepare("SET NAMES 'utf8'");
      $setnames->execute();

      $this->connection = $conn;
    } 
    catch(\mysqli_sql_exception $e)
    {
      die("Connection failed: {$e->getMessage()}");
    }
  }

  public function getConnection(): object
  {
    return $this->connection;
  }
}