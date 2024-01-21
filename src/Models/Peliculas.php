<?php
namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Peliculas
{
    private BaseDatos $db;
    private $isan;
    private $titulo;
    private $director;
    private $genero;
    private $año;

    public function __construct()
    {
        $this->db = new BaseDatos();
    }

    // Getters
    public function getIsan()
    {
        return $this->isan;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDirector()
    {
        return $this->director;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function getAño()
    {
        return $this->año;
    }

    // Setters
    public function setIsan($isan)
    {
        $this->isan = $isan;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function setDirector($director)
    {
        $this->director = $director;
    }

    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    public function setAño($año)
    {
        $this->año = $año;
    }

    // Métodos
    public function getAll()
    {
        $db = new BaseDatos();
        $select = $db->preparada("SELECT * FROM fondos");
        $select->execute();
        $result = $select->fetchAll(PDO::FETCH_ASSOC);
        $select->closeCursor();
        $select = null;
        $db->cierraConexion();

        return $result;
    }

    public static function buscarPeliculas($titulo){

        $db = new BaseDatos();
        $select = $db->preparada("SELECT * FROM fondos WHERE titulo LIKE :titulo");
        $select->bindValue(':titulo', '%' . $titulo . '%', PDO::PARAM_STR);
        $select->execute();
        $result = $select->fetchAll(PDO::FETCH_ASSOC);
        $select->closeCursor();
        $select = null;
        $db->cierraConexion();

        return $result;

    }

    public function ordenarPeliculas(){
        $db = new BaseDatos();
        $select = $db->preparada("SELECT * FROM fondos ORDER BY titulo");
        $select->execute();
        $result = $select->fetchAll(PDO::FETCH_ASSOC);
        $select->closeCursor();
        $select = null;
        $db->cierraConexion();

        return $result;
    }
}