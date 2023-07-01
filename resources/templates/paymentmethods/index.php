<?php $this->layout('base', ['title' => 'Métodos de pago']) ?>

<?php $this->start('currentPage') ?>paymentmethods<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
  <div class="d-flex justify-content-end">
    <a href="/paymentmethods/create" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i>
      Nuevo
    </a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($paymentMethods as $paymentMethod): ?>
        <tr>
          <td><?= $paymentMethod->getName()?></td>
          <td><?= $paymentMethod->getDescription()?></td>
          <td>
            <?php if ($paymentMethod->getState() === true): ?>
              <span class="badge text-bg-success">activo</span>              
            <?php else: ?>
              <span class="badge text-bg-danger">inactivo</span>               
            <?php endif; ?>
          </td>
          <td>
            <div class="d-flex gap-2">
              <a href="<?= 'paymentmethods/edit/' . $paymentMethod->getId() ?>" class="btn btn-info" title="Editar">
                <i class="bi bi-pencil-square"></i>
              </a>      
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