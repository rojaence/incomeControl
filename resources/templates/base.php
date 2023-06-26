<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/css/base.css">
  <title><?= $this->e($title) ?></title>
</head>
<body>
  <header class="header">
    <button id="sidebar-toggle-on" class="btn btn-primary d-md-none">
      <i class="bi bi-list"></i>
    </button>
    <h1>Income Control</h1>
  </header>

  <div id="sidebar" class="sidebar sidebar--close">
    <div class="d-flex justify-content-between sticky-top p-2" style="background-color: var(--bs-secondary-bg);">
      <h3>Finances</h3>
      <button id="sidebar-toggle-off" class="btn btn-outline-danger d-md-none">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="/incomes">Ingresos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/withdrawals">Egresos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/paymentmethods">Métodos de pago</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/transactiontypes">Tipos de transacción</a>
      </li>
    </ul>
  </div>


  <main class="main">
    
    <?=$this->section('mainContent')?>
  </main>

  <footer>
    Coded by Ronny Endara
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <script src="/js/base.js"></script>
</body>
</html>