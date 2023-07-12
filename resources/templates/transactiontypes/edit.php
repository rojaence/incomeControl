<?php $this->layout('base', ['title' => "Tipos de transacción | Editar ({$transactionType->getName()})"]) ?>

<?php $this->start('mainContent'); ?>
<div class="row justify-content-center">
  <div class="col-12 col-lg-9 col-xl-8">
    <?php if (isset($formError)): ?>
      <div class="alert alert-danger" role="alert">
        <?= $formError ?>
      </div>
    <?php endif; ?>
    <form action="/transactiontypes/edit" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" name="name" id="name" value="<?= $transactionType->getName() ?>">
      </div>
      <div class="mb-3 ">
        <label for="description" class="form-label">Descripción</label>
        <textarea rows="8" type="text" class="form-control" name="description" id="description"><?= $transactionType->getDescription() ?></textarea>
      </div>
      <div class="mb-3 form-check">
        <input class="form-check-input" type="checkbox" name="state" id="state" <?php if ($transactionType->getState() == 1) { echo "checked"; } ?> >
        <label for="state" class="form-check-label">Activo</label>
      </div>
      <input type="hidden" name="method" value="put">
      <input type="hidden" name="id" value="<?= $transactionType->getId() ?>">
      <div class="d-flex gap-2 justify-content-end">
        <a class="btn btn-danger" href="/transactiontypes">
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