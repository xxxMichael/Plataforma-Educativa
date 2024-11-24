<?php

class Conexion
{
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "davidgiler21";
    private $db = "proyecto_fundamentos";
    private $conect;


    public function __construct()
    {
        $conectionString = "mysql:hos=" . $this->host . ";dbname=" . $this->db . ";charset=utf8";
        try {
            $this->conect = new PDO($conectionString, $this->user, $this->password);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            #echo "Conexion Exitosa";
        } catch (Exception $e) {
            $this->conect = "Error de conexion";
            echo "Error: " . $e->getMessage();
        }
    }

    public function getConect()
    {
        return $this->conect;
    }

}

?>