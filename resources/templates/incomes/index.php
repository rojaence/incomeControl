<?php $this->layout('base', ['title' => 'Ingresos']) ?>

<?php $this->start('currentPage') ?>incomes<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
  <div class="d-flex justify-content-end">
    <a href="#" class="btn btn-primary">
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
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($incomes as $income): ?>
        <tr>
          <td><?= $income->getAmount() ?></td>
          <td><?= $income->getDescription() ?></td>
          <td><?= $income->getDate() ?></td>
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