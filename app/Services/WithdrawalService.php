<?php

namespace App\Services;
use App\Models\WithdrawalModel;
use App\Exceptions\DataTypeException;
use InvalidArgumentException;

class WithdrawalService extends BaseService
{
  public function getAll(): array
  {
    $query = $this->dbConnection->prepare("SELECT * FROM withdrawal");
    $query->execute();
    $withdrawals = $query->fetchAll(\PDO::FETCH_ASSOC);
    $withdrawals = array_map(function ($withdrawal) {
      return WithdrawalModel::fromArray($withdrawal);
    }, $withdrawals);
    return $withdrawals;
  }

  public function getById($id)
  {
    $query = $this->dbConnection->prepare("SELECT * FROM withdrawal WHERE id = :id");
    $query->bindValue(":id", $id, \PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(\PDO::FETCH_ASSOC);
    return WithdrawalModel::fromArray($result);
  }

  public function create($data) 
  {
    $this->validateWithdrawalData($data);
    $query = $this->dbConnection->prepare("INSERT INTO withdrawal (payment_method_id, transaction_type_id, date, amount, description) VALUES (:payment_method_id, :transaction_type_id, :date, :amount, :description)");
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
    $this->validateWithdrawalData($data);
    $query = $this->dbConnection->prepare("UPDATE withdrawal SET payment_method_id = :payment_method_id, transaction_type_id = :transaction_type_id, date = :date, amount = :amount, description = :description WHERE id = :id");
    $query->bindValue(':payment_method_id', $data->getPaymentMethodId(), \PDO::PARAM_INT);
    $query->bindValue(':transaction_type_id', $data->getTransactionTypeId(), \PDO::PARAM_INT);
    $query->bindValue(':date', $data->getDate(), \PDO::PARAM_STR);
    $query->bindValue(':amount', $data->getAmount(), \PDO::PARAM_STR);
    $query->bindValue(':description', $data->getDescription(), \PDO::PARAM_STR);
    $query->bindValue(':id', $data->getid(), \PDO::PARAM_INT);
    $query->execute();
  }

  public function delete($id)
  {
    $query = $this->dbConnection->prepare("DELETE FROM withdrawal WHERE id = :id");
    $query->bindParam(':id', $id, \PDO::PARAM_INT);
    $query->execute();
  }

  public function idExists($id) {
    $query = $this->dbConnection->prepare("SELECT COUNT(*) AS count_exists FROM withdrawal WHERE id = :id LIMIT 1;
    ");
    $query->bindValue(':id', $id, \PDO::PARAM_INT);
    $query->execute();
    $count = $query->fetchColumn();
    return $count > 0;
  }

  public function validateWithdrawalData($data) {
    if (!$data instanceof WithdrawalModel) {
      throw new DataTypeException('Se esperaba una instancia de WithdrawalModel');
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