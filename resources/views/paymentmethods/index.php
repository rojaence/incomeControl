<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Métodos de pago</title>
</head>
<body>
  <main>
    <h1>Métodos de pago</h1>
    <ul>
      <?php foreach($paymentMethods as $paymentMethod): ?>
        <li><?= $paymentMethod->getName()?></li>
      <?php endforeach; ?>
    </ul>
  </main>
</body>
</html>