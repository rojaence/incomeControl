<?php $this->layout('base', ['title' => 'Inicio']) ?>

<?php $this->start('currentPage') ?>home<?php $this->stop() ?>

<?php $this->start('mainContent'); ?>
    <h2>Bienvenido a mi página de inicio</h2>
    <p>Página de inicio con motor de plantillas en php</p>
<?php $this->end(); ?>

