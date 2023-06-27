<?php $this->layout('base', ['title' => 'Ingresos']) ?>

<?php $this->start('currentPage') ?>incomes<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
  <h1>Lista de ingresos</h1>
  <table>
    <tbody>
      <tr>
        <td>Monto</td>
        <td>Concepto</td>
        <td>Fecha</td>
      </tr>
      <?php foreach($incomes as $income): ?>
        <tr>
          <td><?= $income->getAmount() ?></td>
          <td><?= $income->getDescription() ?></td>
          <td><?= $income->getDate() ?></td>
        </tr> 
      <?php endforeach; ?>
    </tbody>
  </table>
<?php $this->end(); ?>