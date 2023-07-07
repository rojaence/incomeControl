<?php

namespace App\Services;

use Database\PDO\Connection;

abstract class BaseService
{
  protected $dbConnection;

  public function __construct()
  {
    $connection = Connection::getInstance()->getConnection();
    $this->dbConnection = $connection;
  }

  abstract public function getAll();

  abstract public function getById(int $id);

  abstract public function create($data);

  abstract public function update($data);

  abstract public function delete(int $id);
}