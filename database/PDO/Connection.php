<?php

namespace Database\PDO;

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
      // Verificar si está en entorno de pruebas
      $environment = $_ENV['ENVIRONMENT'];
      $host = '';
      $dbname = '';
      $user = '';
      $password = '';

      if ($environment === 'production' ) {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];
      } else {
        $host = $_ENV['DB_HOST_TEST'];
        $dbname = $_ENV['DB_NAME_TEST'];
        $user = $_ENV['DB_USER_TEST'];
        $password = $_ENV['DB_PASS_TEST'];
      }
      
      $conn = new \PDO("mysql:host={$host};dbname={$dbname}", $user, $password);
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