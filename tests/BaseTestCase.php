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

    $_ENV['ENVIRONMENT'] = 'development';
    $connection = Connection::getInstance()->getConnection();
    $this->dbConnection = $connection;
    $this->truncateDatabase();
  }

  protected function tearDown(): void
  {
    $this->truncateDatabase();
    parent::tearDown();
  }

  protected function truncateDatabase() {
   // Reiniciar los valores autoincrementales y eliminar los datos en cada tabla

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
  }
}