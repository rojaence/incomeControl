<?php $this->layout('base', ['title' => 'Ingresos']) ?>

<?php $this->start('currentPage') ?>incomes<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
  <div class="d-flex justify-content-end">
    <a href="/incomes/create" class="btn btn-primary">
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
      <?php foreach($incomes as $income): ?>
        <tr>
          <td><?= $income->getAmount() ?></td>
          <td><?= $income->getDescription() ?></td>
          <td><?= explode(' ', $income->getDate())[0] ?></td>
          <td><?= $income->getPaymentMethodName() ?></td>
          <td><?= $income->getTransactionTypeName() ?></td>
          <td>
            <div class="d-flex gap-2">
              <a href="<?= '/incomes/edit/' . $income->getId() ?>" class="btn btn-success" title="Editar">
                <i class="bi bi-pencil-square"></i>
              </a>      
              <button class="btn btn-danger" title="Eliminar" onclick="<?= "showDeleteModal({source: 'incomes', id: {$income->getId()}})" ?>">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
          </td>
        </tr> 
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if (isset($toast)): ?>
    <?php $this->insert('partials::toast', ["toast" => $toast]) ?>
  <?php endif; ?>

<?php $this->end(); ?>