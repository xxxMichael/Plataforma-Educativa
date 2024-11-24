<?php
class Curso
{
    private $nombre;
    private $codigo;
    private $docente;
    private $estudiantes;
    private $materias;

    public function __construct($nombre)
    {
        $this->nombre = $nombre;
        $this->codigo = $this->generarCodigo($nombre);
        $this->estudiantes = array();
        $this->materias = array();
    }

    public function generarCodigo($nombre)
    {
        $miConexion = new Conexion();
        $usuario = new Gestor_Conexion();
        $codigo = $usuario->getCodigoCursos($nombre);
        return $codigo;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getCodigo()
    {
        return $this->codigo;
    }
}
?>