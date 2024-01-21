<?php

namespace Controllers;

use Lib\Pages;
use Utils\Utils;
use Models\Carrito;

class CarritoController
{
    private Pages $pages;

    /**
     * Constructor de la clase CategoriaController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    public function verCarrito(){
        $this->pages->render('/Carrito/carrito');
    }
    public function AnadirAlCarrito(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar si se proporcionaron datos de categoría
            if ($_POST['producto']) {
                
                $productoAañadir = $_POST['producto'];

                // Verificar si la variable de sesión 'carrito' existe
                if (isset($_SESSION['carrito'])) {
                    // Si existe, verificar si el producto ya está en el carrito
                    $productoEncontrado = false;
                    foreach ($_SESSION['carrito'] as &$producto) {
                        if ($producto['id'] == $productoAañadir['id']) {
                            // Si el producto ya está en el carrito, incrementar la cantidad
                            $producto['cantidad'] += $productoAañadir['cantidad'];
                            $productoEncontrado = true;
                            break;
                        }
                    }
                    // Si el producto no está en el carrito, añadirlo
                    if (!$productoEncontrado) {
                        $_SESSION['carrito'][] = $productoAañadir;
                    }
                } else {
                    // Si no existe, crearla y añadir el nuevo producto
                    $_SESSION['carrito'] = [$productoAañadir];
                }
                $this->pages->render('/Carrito/carrito');
            }
        }
    }

    public function eliminarDelCarrito($id){
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $key => $producto) {
                if ($producto['id'] == $id) {
                    unset($_SESSION['carrito'][$key]);
                    break;
                }
            }
        }
        $this->pages->render('/Carrito/carrito');
    }

    public function eliminarCarrito(){
        unset($_SESSION['carrito']);
        $this->pages->render('/Carrito/carrito');
    }

    public function anadirUnidad($id){
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as &$producto) {
                if ($producto['id'] == $id) {
                    $producto['cantidad']++;
                    break;
                }
            }
        }
        $this->pages->render('/Carrito/carrito');
    }

    public function restarUnidad($id){
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as &$producto) {
                if ($producto['id'] == $id) {
                    $producto['cantidad']--;
                    break;
                }
            }
        }
        $this->pages->render('/Carrito/carrito');
    }
}