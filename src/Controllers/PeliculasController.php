<?php
namespace Controllers;

use Models\Peliculas;
use Lib\Pages;
use Utils\Utils;

class PeliculasController
{
    private Pages $pages;

    /**
     * Constructor de la clase UserController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Obtener todos los usuarios.
     *
     * @return array Array de usuarios.
     */
    public  function obtenerPeliculas()
    {
        $peliculas = new Peliculas();
        $peliculas = $peliculas->getAll();

        $this->pages->render('/Peliculas/mostrar_todas', ['peliculas' => $peliculas]);
        
    }

    /**
     * Buscar registros según el formulario de búsqueda.
     */
    public  function buscarPeliculas()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buscar'])) {
            $buscar = $_POST['buscar'];
            
            $Peliculas = Peliculas::buscarPeliculas($buscar);
            
        } else {
            $buscar = null;
        }
        $this->pages->render('/Peliculas/mostrar_todas', ['peliculasEncontradas' => $Peliculas, 'buscar' => 'true']); 
    }

    public function ordenarPeliculas(){
        $peliculas = new Peliculas();
        $peliculas = $peliculas->ordenarPeliculas();

        $this->pages->render('/Peliculas/mostrar_todas', ['peliculas' => $peliculas]);
    }

    
}