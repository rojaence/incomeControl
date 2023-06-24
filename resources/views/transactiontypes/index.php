<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tipos de transacción</title>
</head>
<body>
  <main>
    <h1>Tipos de transacción</h1>
    <ul>
      <?php foreach($transactionTypes as $transactionType): ?>
        <li><?= $transactionType->getName() ?></li>
      <?php endforeach; ?>
    </ul>
  </main>
</body>
</html>