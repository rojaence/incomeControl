<?php $this->layout('base', ['title' => 'Registrar ingreso']) ?>

<?= $this->start('mainContent') ?>
<div class="row justify-content-center">
  <div class="col-12 col-lg-9 col-xl-8">
    <?php if (isset($formError)): ?>
      <div class="alert alert-danger" role="alert">
        <?= $formError ?>
      </div>
    <?php endif; ?>
    <form action="/incomes/create" method="post" id="income-form">
      <div class="row">
        <div class="col-12 col-md-5 col-lg-4 mb-4">
          <label for="date" class="form-label">Fecha</label>
          <input type="date" id="date-picker" name="date" class="form-control" 
            value="">
        </div>
        <div class="col-12 col-md-6 col-lg-5 mb-4">
          <label for="amount" class="form-label">Monto</label>
          <input type="number" class="form-control" name="amount" id="amount">
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-5 col-lg-5 mb-4">
          <label for="payment-method" class="form-label">Método de pago</label>
          <select class="form-select" aria-label="Payment Method Select" name="payment_method_id" id="payment-method">
            <?php foreach($paymentMethods as $paymentMethod): ?>
              <option value="<?= $paymentMethod->getId() ?>"><?= $paymentMethod->getName() ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12 col-md-5 col-lg-5 mb-4">
          <label for="transaction-type" class="form-label">Tipo de transacción</label>
          <select class="form-select" aria-label="Transaction Type Select" name="transaction_type_id" id="transaction-type">
          <?php foreach($transactionTypes as $transactionType): ?>
              <option value="<?= $transactionType->getId() ?>"><?= $transactionType->getName() ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea rows="8" type="text" class="form-control" name="description" id="description"></textarea>
      </div>
      <input type="hidden" name="method" value="post">
      <div class="d-flex gap-2 justify-content-end">
        <a class="btn btn-danger" href="/incomes">
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

<script>
  window.onload = () => {
    const dateInput = document.querySelector('#date-picker')
    const currentDate = new Date()
    const formattedDate = currentDate.toLocaleDateString('en-CA');
    dateInput.value = formattedDate
  }
</script>
<?php $this->end(); ?>