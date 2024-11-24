<?php
class Asignatura
{
    private $nombre;
    private $codigo;
    private $correodocente;
    private $codigoCurso;
    public function __construct($nombre, $codigo, $correodocente, $codigoCurso)
    {
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->correodocente = $correodocente;
        $this->codigoCurso = $codigoCurso;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getCodigo()
    {
        return $this->codigo;
    }
    public function getCorreoDocente()
    {
        return $this->correodocente;
    }

    public function getCodigoCurso()
    {
        return $this->codigoCurso;
    }

    public function toArray()
    {
        return [
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'correodocente' => $this->correodocente,
            'codigoCurso' => $this->codigoCurso,
        ];
    }
}
?>