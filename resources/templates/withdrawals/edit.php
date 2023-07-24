<?php $this->layout('base', ['title' => "Editar egreso"]) ?>

<?php $this->start('mainContent'); ?>
<div class="row justify-content-center">
  <div class="col-12 col-lg-9 col-xl-8">
    <?php if (isset($formError)): ?>
      <div class="alert alert-danger" role="alert">
        <?= $formError ?>
      </div>
    <?php endif; ?>
    <form action="/withdrawals/edit" method="post" id="withdrawal-form">
      <div class="row">
        <div class="col-12 col-md-5 col-lg-4 mb-4">
          <label for="date" class="form-label">Fecha</label>
          <input type="date" id="date-picker" name="date" class="form-control" 
            value="<?= date('Y-m-d', strtotime($withdrawal->getDate())) ?>">
        </div>
        <div class="col-12 col-md-6 col-lg-5 mb-4">
          <label for="amount" class="form-label">Monto</label>
          <input type="number" class="form-control" name="amount" id="amount" value="<?= $withdrawal->getAmount() ?>">
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-5 col-lg-5 mb-4">
          <label for="payment-method" class="form-label">Método de pago</label>
          <select class="form-select" aria-label="Payment Method Select" name="payment_method_id" id="payment-method" value="<?= $withdrawal->getPaymentMethodId() ?>">
            <?php foreach($paymentMethods as $paymentMethod): ?>
              <option value="<?= $paymentMethod->getId() ?>"><?= $paymentMethod->getName() ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12 col-md-5 col-lg-5 mb-4">
          <label for="transaction-type" class="form-label">Tipo de transacción</label>
          <select class="form-select" aria-label="Transaction Type Select" name="transaction_type_id" id="transaction-type" value="<?= $withdrawal->getTransactionTypeId() ?>">
          <?php foreach($transactionTypes as $transactionType): ?>
              <option value="<?= $transactionType->getId() ?>"><?= $transactionType->getName() ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea rows="8" type="text" class="form-control" name="description" id="description"><?= $withdrawal->getDescription() ?></textarea>
      </div>
      <input type="hidden" name="method" value="put">
      <input type="hidden" name="id" value="<?= $withdrawal->getId() ?>">
      <div class="d-flex gap-2 justify-content-end">
        <a class="btn btn-danger" href="/withdrawals">
          <i class="bi bi-x-lg"></i>
          Cancelar
        </a>
        <button type="submit" class="btn btn-primary" id="submit">
          <i class="bi bi-check-lg"></i>
          Guardar
        </button>
      </div>
    </form>
  </div>
</div>
<?php $this->end(); ?>