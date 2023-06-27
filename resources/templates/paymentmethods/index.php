<?php $this->layout('base', ['title' => 'Métodos de pago']) ?>

<?php $this->start('currentPage') ?>paymentmethods<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
  <h1>Métodos de pago</h1>
  <ul>
    <?php foreach($paymentMethods as $paymentMethod): ?>
      <li><?= $paymentMethod->getName()?></li>
    <?php endforeach; ?>
  </ul>
<?php $this->end(); ?>