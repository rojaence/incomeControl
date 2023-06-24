<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ingresos</title>
</head>
<body>
  <main>
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
  </main>
</body>
</html>