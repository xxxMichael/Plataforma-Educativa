<?php
require_once("../Objetos/Persona.php");
class Estudiante extends Persona implements JsonSerializable 
  {
  //  private $promedio;
  private $materias;
  private $cursos;

  public function __construct($nombre, $apellido, $correo, $clave)
  {
    parent::__construct($nombre, $apellido, $correo, $clave);
    //  $this->promedio = 0;
    $this->materias = array();
    $this->cursos = array();
  }

  // public function getPromedio()
  //{
  //  return $this->promedio;
  // }

  public function getCursos()
  {
    return $this->cursos;
  }

  public function getMaterias()
  {
    return $this->materias;
  }
  public function jsonSerialize() {
    return [
        'nombre' => $this->getNombre(),
        'apellido' => $this->getApellido(),
        'clave' => $this->getClave(),
        // Otras propiedades necesarias
    ];
  }
}
?>