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

<div id="sidebar" class="sidebar sidebar--close">
    <div class="d-flex justify-content-between sticky-top p-2" style="background-color: var(--bs-secondary-bg);">
      <h3>Menú</h3>
      <button id="sidebar-toggle-off" class="btn btn-outline-danger d-md-none">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?= ($this->section('currentPage') === 'home') ? 'nav-link--active' : '' ?>" href="/">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($this->section('currentPage') === 'incomes') ? 'nav-link--active' : '' ?>" href="/incomes">Ingresos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($this->section('currentPage') === 'withdrawals') ? 'nav-link--active' : '' ?>" href="/withdrawals">Egresos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($this->section('currentPage') === 'paymentmethods') ? 'nav-link--active' : '' ?>" href="/paymentmethods">Métodos de pago</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= ($this->section('currentPage') === 'transactiontypes') ? 'nav-link--active' : '' ?>" href="/transactiontypes">Tipos de transacción</a>
      </li>
    </ul>
  </div>

  <header class="header shadow-sm">

    <button id="sidebar-toggle-on" class="btn btn-primary d-md-none">
      <i class="bi bi-list"></i>
    </button>

    <h1 class="fs-3">
      <?= $this->e($title) ?>
    </h1>

    <div class="ms-auto dropdown" id="theme-selector" title="Tema">
      <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i id="theme-icon-active" class="bi"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end">
        <li id="item-light"><button class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="light"><i class="bi bi-brightness-high-fill"></i>Claro</button></li>
        <li id="item-dark"><button class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="dark"><i class="bi bi-moon-fill"></i>Oscuro</button></li>
        <li id="item-auto"><button class="dropdown-item d-flex align-items-center gap-2" data-bs-theme-value="auto"><i class="bi bi-circle-half"></i>Auto</button></li>
      </ul>
    </div>
    
  </header>


  <main class="main">
    <div class="container">
      <?=$this->section('mainContent')?>
    </div>
  </main>

  <footer class="footer">
    Coded by Ronny Endara
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <script src="/js/base.js"></script>
</body>
</html>