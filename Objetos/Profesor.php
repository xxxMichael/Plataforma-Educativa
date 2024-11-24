<?php
require_once("../Objetos/Persona.php");
class Profesor extends Persona  implements JsonSerializable {

    private $cursos;

    public function __construct($nombre, $apellido, $correo, $clave)
    {
        parent::__construct($nombre, $apellido, $correo, $clave);
        $this->cursos = array();
    }

    public function getCursos()
    {
        return $this->cursos;
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