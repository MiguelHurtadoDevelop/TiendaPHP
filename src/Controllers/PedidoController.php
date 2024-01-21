<?php

namespace Controllers;

use Lib\Pages;
use Utils\Utils;
use Models\Pedido;
use Controllers\ProductoController;

class PedidoController
{
    private Pages $pages;

    /**
     * Constructor de la clase CategoriaController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    public function pedirDireccion(){
        $this->pages->render('/Pedido/pedirDireccion');
    }

    public function calcularCoste(){
        $coste = 0;
        if (isset($_SESSION['carrito'])) {
            
            foreach ($_SESSION['carrito'] as $producto) {

                $product=ProductoController::obtenerProducto($producto['id']);

                $precioTotal = $product['precio'] * $producto['cantidad'];
                $coste += $precioTotal;
            }
        }
        return $coste;
    }
    public function realizarPedido(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verificar si se proporcionaron datos de categoría
            if ($_POST['direccion']) {
                
                $DireccionEnvio = $_POST['direccion'];

                

                $direccion = $DireccionEnvio['direccion'];

                $provincia = $DireccionEnvio['provincia'];
                $localidad = $DireccionEnvio['localidad'];
                $coste = $this->calcularCoste();
                $usuario_id = $_SESSION['login']->id;
                $estado = 'confirmado';
                $fecha = date('Y-m-d');
                $hora = date('H:i:s');


                // Verificar el stock de cada producto en el carrito
                foreach ($_SESSION['carrito'] as $producto) {
                    $product = ProductoController::obtenerProducto($producto['id']);
                    if ($product['stock'] < $producto['cantidad']) {
                        // No hay suficiente stock para este producto
                        // Puedes decidir qué hacer en este caso, por ejemplo, mostrar un mensaje de error
                        return "No hay suficiente stock para el producto " . $product['nombre'];
                    }
                }

                // Si hay suficiente stock para todos los productos, proceder con el pedido
                foreach ($_SESSION['carrito'] as $producto) {
                    $product = ProductoController::obtenerProducto($producto['id']);
                    // Restar la cantidad pedida al stock del producto
                    $product['stock'] -= $producto['cantidad'];
                    // Actualizar el stock del producto en la base de datos
                    ProductoController::actualizarStock($product);

                    $pedido = new Pedido();

                    

                    

                }

                    $pedido = new Pedido();

                    $pedido->setUsuarioId($usuario_id);
                    $pedido->setProvincia($provincia);
                    $pedido->setLocalidad($localidad);
                    $pedido->setDireccion($direccion);
                    $pedido->setCoste($coste);
                    $pedido->setEstado($estado);
                    $pedido->setFecha($fecha);
                    $pedido->setHora($hora);


                    $pedido->create();

                    // Obtener el ID del pedido que acabamos de crear
                    $ultimoPedido = $pedido->getLast();
                    $pedido_id = $ultimoPedido->id;
                    

                    // Insertar cada línea del pedido en la base de datos
                    foreach ($_SESSION['carrito'] as $producto) {
                        $pedido->createLineaPedido($pedido_id, $producto['id'], $producto['cantidad']);
                        
                    }
                    $nombreUsuario = $_SESSION['login']->nombre;
                    $cuerpoCorreo = "
                            <h1>Detalles del Pedido</h1>
                            <p><strong>Número de Pedido:</strong> {$pedido_id}</p>
                            <p><strong>Nombre del Cliente:</strong> {$nombreUsuario}</p>
                            <p><strong>Importe Total:</strong> {$coste}</p>
                        ";


                        
                    
                    $pedido->enviarCorreo($cuerpoCorreo);

                    Utils::deleteSession('carrito');

                    $this->pages->render('/Carrito/carrito');
                }
        }

    }

    public function gestionarPedidos(){
        $pedido = new Pedido();
        $pedidos = $pedido->getAll();
        
        $this->pages->render('/Pedido/gestionarPedidos', ['pedidos' => $pedidos]);
    }

    public function cambiarAEnviado($id){
        $pedido = new Pedido();
        $pedido->setId($id);
        $pedido->setEstado('enviado');
        $pedido->updateEstado();
        $this->gestionarPedidos();
    }

    public function cambiarATerminado($id){
        $pedido = new Pedido();
        $pedido->setId($id);
        $pedido->setEstado('terminado');
        $pedido->updateEstado();
        $this->gestionarPedidos();
    }

    public function verMisPedidos(){
        $pedido = new Pedido();
        $pedidos = $pedido->getByUser($_SESSION['login']->id);
        
        $this->pages->render('/Pedido/verMisPedidos', ['pedidos' => $pedidos]);
    }
}