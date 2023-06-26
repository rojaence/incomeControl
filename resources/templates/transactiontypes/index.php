<?php $this->layout('base', ['title' => 'Tipos de transacción']) ?>

<?php $this->start('mainContent'); ?>
  <h1>Tipos de transacción</h1>
  <ul>
    <?php foreach($transactionTypes as $transactionType): ?>
      <li><?= $transactionType->getName() ?></li>
    <?php endforeach; ?>
  </ul>
<?php $this->end(); ?>