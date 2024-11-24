<?php
class Tarea
{
    private $correoEstudiante;
    private $nombreTarea;
    private $codigoAsignatura;
    private $archivo;
    private $calificacion;
    private $comentario;
    private $fecha_envio;

    public function __construct($correoEstudiante, $nombreTarea, $codigoAsignatura, $archivo, $calificacion, $comentario, $fecha_envio)
    {
        $this->correoEstudiante = $correoEstudiante;
        $this->nombreTarea = $nombreTarea;
        $this->codigoAsignatura = $codigoAsignatura;
        $this->archivo = $archivo;
        $this->calificacion = $calificacion;
        $this->comentario = $comentario;
        $this->fecha_envio = $fecha_envio;
    }

    public function getCorreoEstudiante()
    {
        return $this->correoEstudiante;
    }

    public function getNombreTarea()
    {
        return $this->nombreTarea;
    }

    public function getCodigoAsignatura()
    {
        return $this->codigoAsignatura;
    }

    public function getArchivo()
    {
        return $this->archivo;
    }

    public function getCalificacion()
    {
        return $this->calificacion;
    }

    public function getComentario()
    {
        return $this->comentario;
    }

    public function getFechaEnvio()
    {
        return $this->fecha_envio;
    }
}
?>