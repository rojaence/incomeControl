<?php

namespace App\COntrollers;

abstract class BaseController
{
  protected $dbConnection;

  public function __construct()
  { 
    // TODO: configurar la conexión a la base de datos
    $this->dbConnection = "";
  }

  // Métodos de Controlador genéricos

  /**
   * Muestra una lista del recurso
  */
  abstract public function index();

  /**
   * Muestra un único recurso
  */
  abstract public function show();

  /**
   * Muestra un formulario para crear un nuevo recurso
  */
  public function create()
  {

  }

  /**
   * Guarda un nuevo recurso en la base de datos
  */
  public function store()
  {
    
  }


  /**
   * Muestra el formulario para editar un único recurso
  */
  public function edit()
  {
    
  }

  /**
   * Actualiza un recurso en la base de datos
  */
  public function update()
  {
    
  }

  /**
   * Elimina un recurso en la base de datos
  */
  public function destroy()
  {
    
  }
}