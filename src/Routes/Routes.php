<?php

namespace Routes;

use Controllers\DashboardController;
use Controllers\ErrorController;
use Lib\Router;
use Controllers\CategoriaController;
use Controllers\ProductoController;
use Controllers\UserController;
use Controllers\CarritoController;
use Controllers\PedidoController;
class Routes
{
    public static function index(){
        Router::add('GET', '/', function(){
            return(new UserController())->login();

        });

        Router::add('GET', '/dashboard/index', function(){
            return(new DashboardController())->index();
        });

        Router::add('GET', '/User/login/', function(){
            return(new UserController())->login();
        });
        Router::add('POST', '/User/login/', function(){
            return(new UserController())->login();
        });
        Router::add('GET', '/User/registrarse', function(){
            return(new UserController())->registrarse();
        });

        Router::add('POST', '/User/registro', function(){
            return(new UserController())->registro();
        });


        Router::add('GET', '/User/gestionarUsuarios', function(){
            return(new UserController())->gestionarUsuarios();
        });

        Router::add('GET', '/User/hacerAdmin/:id', function($id){
            
            return(new UserController())->hacerAdmin($id);
        });

        Router::add('GET', '/User/logout', function(){
            return(new UserController())->logout();
        });

        Router::add('GET', '/User/editarUsuario', function(){
            return(new UserController())->editarUsuario();
        });

        Router::add('POST', '/User/actualizarUsuario', function(){
            return(new UserController())->actualizarUsuario();
        });

        
        Router::add('GET', '/Categoria/gestionarCategoria', function(){
            return(new CategoriaController())->gestionarCategoria();
        });

        Router::add('GET', '/Categoria/addCategory', function(){
            return(new CategoriaController())->addCategory();
        });

        Router::add('POST', '/Categoria/AnadirCategoria', function(){
            return(new CategoriaController())->AnadirCategoria();
        });

        Router::add('GET', '/Categoria/editarCategoria/:id', function($id){
            return(new CategoriaController())->editarCategoria($id);
        });

        Router::add('POST', '/Categoria/actualizarCategoria', function(){
            return(new CategoriaController())->actualizarCategoria();
        });

        Router::add('GET', '/Categoria/borrarCategoria/:id', function($id){
            return(new CategoriaController())->borrarCategoria($id);
        });

        
        Router::add('GET', '/Producto/mostrarProductos', function(){
            return(new ProductoController())->mostrarProductos();
        });

        Router::add('GET', '/Producto/mostrarProductos/:id', function($id){
            return(new ProductoController())->mostrarProductos($id);
        });

        Router::add('GET', '/Producto/gestionarProductos', function(){
            return(new ProductoController())->gestionarProductos();
        });

        Router::add('GET', '/Producto/addProduct', function(){
            return(new ProductoController())->addProduct();
        });
        
        Router::add('POST', '/Producto/AnadirProducto', function(){
            return(new ProductoController())->AnadirProducto();
        });

        Router::add('GET', '/Producto/editarProducto/:id', function($id){
            return(new ProductoController())->editarProducto($id);
        });

        Router::add('POST', '/Producto/actualizarProducto', function(){
            return(new ProductoController())->actualizarProducto();
        });

        Router::add('GET', '/Producto/borrarProducto/:id', function($id){
            return(new ProductoController())->borrarProducto($id);
        });

        Router::add('GET', '/Producto/verProducto/:id', function($id){
            return(new ProductoController())->verProducto($id);
        });

        Router::add('POST', '/Carrito/AnadirAlCarrito', function(){
            return(new CarritoController())->AnadirAlCarrito();
        });


        Router::add('GET', '/Carrito/verCarrito', function(){
            return(new CarritoController())->verCarrito();
        });

        Router::add('GET', '/Carrito/eliminarDelCarrito/:id', function($id){
            return(new CarritoController())->eliminarDelCarrito($id);
        });

        Router::add('GET', '/Carrito/eliminarCarrito', function(){
            return(new CarritoController())->eliminarCarrito();
        });

        Router::add('GET', '/Carrito/anadirUnidad/:id', function($id){
            return(new CarritoController())->anadirUnidad($id);
        });

        Router::add('GET', '/Carrito/restarUnidad/:id', function($id){
            return(new CarritoController())->restarUnidad($id);
        });

        
        
        Router::add('GET', '/Pedido/pedirDireccion', function(){
            return(new PedidoController())->pedirDireccion();
        });



        Router::add('POST', '/Pedido/RealizarPedido', function(){
            return(new PedidoController())->realizarPedido();
        });

        Router::add('GET', '/Pedido/gestionarPedidos', function(){
            return(new PedidoController())->gestionarPedidos();
        });



        Router::add('GET', '/Pedido/cambiarAEnviado/:id', function($id){
            
            return(new PedidoController())->cambiarAEnviado($id);
        });

        Router::add('GET', '/Pedido/cambiarATerminado/:id', function($id){
            
            return(new PedidoController())->cambiarATerminado($id);
        });

        Router::add('GET', '/Pedido/verMisPedidos', function(){
            
            return(new PedidoController())->verMisPedidos();
        });

        Router::add('GET','/error/', function(){
            return(new ErrorController())->error404();
        });

        Router::dispatch();
    }
}