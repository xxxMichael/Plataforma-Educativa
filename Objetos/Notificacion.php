<?php

class Notificacion
{
    private $notificacion;
    private $mensaje;
    private $fecha;

    // Constructor para inicializar los atributos
    public function __construct($notificacion, $mensaje, $fecha)
    {
        $this->notificacion = $notificacion;
        $this->mensaje = $mensaje;
        $this->fecha = $fecha;
    }

    // Obtener el valor del atributo "notificacion"
    public function getNotificacion()
    {
        return $this->notificacion;
    }

    // Obtener el valor del atributo "mensaje"
    public function getMensaje()
    {
        return $this->mensaje;
    }

    // Obtener el valor del atributo "fecha"
    public function getFecha()
    {
        return $this->fecha;
    }


    public function toArray()
    {
        return [
            'notificacion' => $this->notificacion,
            'mensaje' => $this->mensaje,
            'fecha' => $this->fecha,
        ];
    }
}
?>