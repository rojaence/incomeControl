<?php $this->layout('base', ['title' => 'Métodos de pago']) ?>

<?php $this->start('currentPage') ?>paymentmethods<?php $this->stop() ?>

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
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($paymentMethods as $paymentMethod): ?>
        <tr>
          <td><?= $paymentMethod->getName()?></td>
          <td><?= $paymentMethod->getDescription()?></td>
          <td>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-success" title="Editar">
                <i class="bi bi-pencil-square"></i>
              </a>      
              <button class="btn btn-success" title="Deshabilitar">
                Activo
              </button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php $this->end(); ?>