<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Database\PDO\Connection;
use Dotenv\Dotenv;

class BaseTestCase extends TestCase
{
  protected $dotenv;
  protected $dbConnection;

  protected function setUp(): void
  {
      parent::setUp();

      $this->dotenv = Dotenv::createImmutable(__DIR__ . '/..');
      $this->dotenv->load();

      $connection = Connection::getInstance()->getConnection();
      $this->dbConnection = $connection;
  }

  protected function tearDown(): void
  {
    // Reiniciar los valores autoincrementales y eliminar los datos en cada tabla
    /* $this->dbConnection->execute("TRUNCATE TABLE payment_method");
    $this->dbConnection->execute("TRUNCATE TABLE transaction_type");
    $this->dbConnection->execute("TRUNCATE TABLE income");
    $this->dbConnection->execute("TRUNCATE TABLE withdrawal");
    $this->dbConnection->disconnect(); */

    $disableFKCheck = $this->dbConnection->prepare("SET FOREIGN_KEY_CHECKS=0");
    $enableFKCheck = $this->dbConnection->prepare("SET FOREIGN_KEY_CHECKS=1");

    $truncateStatements = [
      "TRUNCATE TABLE payment_method",
      "TRUNCATE TABLE transaction_type",
      "TRUNCATE TABLE income",
      "TRUNCATE TABLE withdrawal"
    ];

    $disableFKCheck->execute();
    foreach ($truncateStatements as $statement) {
        $stmt = $this->dbConnection->prepare($statement);
        $stmt->execute();
    }
    $enableFKCheck->execute();
    parent::tearDown();
  }
}