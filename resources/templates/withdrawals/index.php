<?php $this->layout('base', ['title' => 'Egresos']) ?>

<?php $this->start('currentPage') ?>withdrawals<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
  <h1>Lista de egresos</h1>
  <table>
    <tbody>
      <tr>
        <td>Monto</td>
        <td>Concepto</td>
        <td>Fecha</td>
      </tr>
      <?php foreach($withdrawals as $withdrawal): ?>
        <tr>
          <td><?= $withdrawal->getAmount() ?></td>
          <td><?= $withdrawal->getDescription() ?></td>
          <td><?= $withdrawal->getDate() ?></td>
        </tr> 
      <?php endforeach; ?>
    </tbody>
  </table>
<?php $this->end(); ?>