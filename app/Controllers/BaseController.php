<?php

namespace App\Controllers;

use Database\PDO\Connection;

abstract class BaseController
{
  protected $dbConnection;

  public function __construct()
  { 
    $connection = Connection::getInstance()->getConnection();
    $this->dbConnection = $connection;
  }

  // Métodos de Controlador genéricos

  /**
   * Muestra una lista del recurso
  */
  abstract public function index();

  /**
   * Muestra un único recurso
  */
  abstract public function show($id);

  /**
   * Muestra un formulario para crear un nuevo recurso
  */
  public function create()
  {

  }

  /**
   * Guarda un nuevo recurso en la base de datos
   * @param $data;
  */
  public function store($data)
  {
    
  }


  /**
   * Muestra el formulario para editar un único recurso
  */
  public function edit($id)
  {
    
  }

  /**
   * Actualiza un recurso en la base de datos
  */
  public function update($data)
  {
    
  }

  /**
   * Elimina un recurso en la base de datos
  */
  public function destroy($id)
  {
    
  }

  /**
   * Redirige a una página
  */
  protected function redirectTo(string $url)
  {
    header("Location: " . $url);
    // exit;
  }
}