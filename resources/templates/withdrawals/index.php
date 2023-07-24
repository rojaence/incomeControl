<?php $this->layout('base', ['title' => 'Egresos']) ?>

<?php $this->start('currentPage') ?>withdrawals<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
  <div class="d-flex justify-content-end">
    <a href="/withdrawals/create" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i>
      Nuevo
    </a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Monto</th>
        <th>Concepto</th>
        <th>Fecha</th>
        <th>Método de pago</th>
        <th>Tipo de transacción</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($withdrawals as $withdrawal): ?>
        <tr>
          <td><?= $withdrawal->getAmount() ?></td>
          <td><?= $withdrawal->getDescription() ?></td>
          <td><?= $withdrawal->getDate() ?></td>
          <td><?= $withdrawal->getPaymentMethodName() ?></td>
          <td><?= $withdrawal->getTransactionTypeName() ?></td>
          <td>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-success" title="Editar">
                <i class="bi bi-pencil-square"></i>
              </a>      
              <a href="#" class="btn btn-danger" title="Eliminar">
                <i class="bi bi-x-lg"></i>
              </a>
            </div>
          </td>
        </tr> 
      <?php endforeach; ?>
    </tbody>
  </table>
<?php $this->end(); ?>