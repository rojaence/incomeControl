<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Egresos</title>
</head>
<body>
  <main>
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
  </main>
</body>
</html>