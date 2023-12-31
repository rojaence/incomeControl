<?php

namespace App\Services;
use App\Models\IncomeModel;
use App\Exceptions\DataTypeException;
use App\Exceptions\IdNotFoundException;
use InvalidArgumentException;

class IncomeService extends BaseService
{
  public function getAll(): array
  {
    $query = $this->dbConnection->prepare("SELECT * FROM income");
    $query->execute();
    $incomes = $query->fetchAll(\PDO::FETCH_ASSOC);
    $incomes = array_map(function ($income) {
      return IncomeModel::fromArray($income);
    }, $incomes);
    return $incomes;
  }

  public function getById(int $id)
  {
    $query = $this->dbConnection->prepare("SELECT * FROM income WHERE id = :id");
    $query->bindValue(":id", $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    return IncomeModel::fromArray($result);
  }

  public function create($data)
  {
    $this->validateIncomeData($data);
    $query = $this->dbConnection->prepare("INSERT INTO income (payment_method_id, transaction_type_id, date, amount, description) VALUES (:payment_method_id, :transaction_type_id, :date, :amount, :description)");
    $query->bindValue(":payment_method_id", $data->getPaymentMethodId(), \PDO::PARAM_INT);
    $query->bindValue(":transaction_type_id", $data->getTransactionTypeId(), \PDO::PARAM_INT);
    $query->bindValue(":date", $data->getDate(), \PDO::PARAM_STR);
    $query->bindValue(":amount", $data->getAmount(), \PDO::PARAM_STR);
    $query->bindValue(":description", $data->getDescription(), \PDO::PARAM_STR);
    $query->execute();
    $data->setId($this->dbConnection->lastInsertId());
  }

  public function update($data)
  {
    $this->validateIncomeData($data);
    $query = $this->dbConnection->prepare("UPDATE income SET payment_method_id = :payment_method_id, transaction_type_id = :transaction_type_id, date = :date, amount = :amount, description = :description WHERE id = :id");
    $query->bindValue(':payment_method_id', $data->getPaymentMethodId(), \PDO::PARAM_INT);
    $query->bindValue(':transaction_type_id', $data->getTransactionTypeId(), \PDO::PARAM_INT);
    $query->bindValue(':date', $data->getDate(), \PDO::PARAM_STR);
    $query->bindValue(':amount', $data->getAmount(), \PDO::PARAM_STR);
    $query->bindValue(':description', $data->getDescription(), \PDO::PARAM_STR);
    $query->bindValue(':id', $data->getid(), \PDO::PARAM_INT);
    $query->execute();
  }

  public function delete(int $id)
  {
    if (!$this->idExists($id)) {
      throw new IdNotFoundException("ID {$id} does not exist");
    }
    $query = $this->dbConnection->prepare("DELETE FROM income WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function idExists($id) {
    $query = $this->dbConnection->prepare("SELECT COUNT(*) AS count_exists FROM income WHERE id = :id LIMIT 1;
    ");
    $query->bindValue(':id', $id, \PDO::PARAM_INT);
    $query->execute();
    $count = $query->fetchColumn();
    return $count > 0;
  }

  public function validateIncomeData($data) {
    if (!$data instanceof IncomeModel) {
      throw new DataTypeException('Se esperaba una instancia de IncomeModel');
    }
    if (!is_numeric($data->getAmount())) {
      throw new InvalidArgumentException('El valor del monto no es válido');
    }
    if ($data->getAmount() <= 0) {
      throw new InvalidArgumentException('El monto debe ser mayor que cero');
    }
    if (empty(trim($data->getDescription()))) {
      throw new InvalidArgumentException('La descripción no puede estar vacía');
    }
    if (empty($data->getPaymentMethodId())) {
      throw new InvalidArgumentException('No se ha establecido un método de pago');
    }
    if (empty($data->getTransactionTypeId())) {
      throw new InvalidArgumentException('No se ha establecido un tipo de transacción');
    }
  }
}