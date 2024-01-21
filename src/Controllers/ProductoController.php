<?php

namespace Controllers;

use Lib\Pages;
use Models\Producto;

class ProductoController
{
    private Pages $pages;

    /**
     * Constructor de la clase ProductoController.
     */
    public function __construct()
    {
        $this->pages = new Pages();
    }

    /**
     * Obtener todos los productos.
     *
     * @return array|null Array de productos o null si no se encuentran.
     */
    public function obtenerProductos(): ?array
    {
        return Producto::getAll();
    }

    public static function obtenerProducto($id): ?array
    {
        return Producto::getById($id);
    }

    /**
     * Obtener todos los productos por categoría.
     *
     * @param int $categoriaId ID de la categoría.
     * @return array|null Array de productos o null si no se encuentran.
     */
    public function obtenerProductosPorCategoria($categoriaId): ?array
    {
        return Producto::getByCategoria($categoriaId);
    }

    /**
     * Renderizar la vista de mostrar productos.
     */
    public function mostrarProductos($categoriaId = null)
    {
        if ($categoriaId !== null) {
            // Si se proporciona el ID de la categoría, obtener productos por categoría
            $productos = $this->obtenerProductosPorCategoria($categoriaId);
        } else {
            // Si no se proporciona el ID de la categoría, obtener todos los productos
            $productos = $this->obtenerProductos();
        }

        // Puedes ajustar la ruta según la estructura de tu proyecto
        $this->pages->render('/producto/mostrarProductos', ['productos' => $productos]);
    }


    /**
     * Renderizar la página de gestión de productos.
     */
    public function gestionarProductos()
    {
        $productos = $this->obtenerProductos();
        $this->pages->render('/producto/productoAdmin'  , ['productos' => $productos]);
    }

    /**
     * Renderizar la página para agregar un producto.
     */
    public function addProduct()
    {
        $this->pages->render('/producto/añadirProducto');
    }

    /**
     * Añadir un nuevo producto basado en el envío del formulario.
     */
    public function AnadirProducto(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if (!empty($_POST['producto'])){
                $productoData = $_POST['producto'];
                
                
                
                $nombre = $productoData['nombre'];
                $descripcion = $productoData['descripcion'];
                $categoriaId = $productoData['categoria'];
                $precio = $productoData['precio'];
                $stock = $productoData['stock'];
                $oferta = $productoData['oferta'];
                $fecha = date("Y-m-d H:i:s");
                $imagen = $_FILES['imagen']['name'];

                

                $productoNuevo = new Producto();

                $productoNuevo->subirFoto($_FILES['imagen']);
    
                // Validar el producto
                $validacion = $productoNuevo->validarProducto($nombre, $descripcion, $precio, $stock, $oferta);
    
                

                if ($validacion['isValid']) {
    
                    $productoSaneado = $productoNuevo->sanearProducto($nombre, $descripcion, $categoriaId, $precio, $stock, $oferta, $fecha, $imagen);
                    
                    
                    $productoNuevo->setNombre($productoSaneado['nombre']);
                    $productoNuevo->setDescripcion($productoSaneado['descripcion']);
                    $productoNuevo->setCategoriaId($productoSaneado['categoriaId']);
                    $productoNuevo->setPrecio($productoSaneado['precio']);
                    $productoNuevo->setStock($productoSaneado['stock']);
                    $productoNuevo->setOferta($productoSaneado['oferta']);
                    $productoNuevo->setFecha($productoSaneado['fecha']);
                    $productoNuevo->setImagen($productoSaneado['imagen']);

    
                    // Si el producto es válido, crea el producto en la base de datos
                    $save = $productoNuevo->create();
    
                    if ($save) {
                        $_SESSION['ProductoAñadido'] = "complete";
                        $this->gestionarProductos();
                    } else {
                        $_SESSION['ProductoAñadido'] = "failed";
                        $this->pages->render('/producto/añadirProducto', ['Producto' => $productoData]);
                    }
                } else {
                    // Si el producto no es válido, guarda el producto en la sesión
                    $_SESSION['ProductoAñadido'] = "failed";
                    $_SESSION['ErrorProducto'] = $validacion['errors'];
                    $_SESSION['Producto'] = $productoData;
                    
                    // Redirige a la vista de añadir producto
                    $this->pages->render('/producto/añadirProducto', ['Producto' => $productoData]);
                }
            } else {
                $_SESSION['ProductoAñadido'] = "failed";
                $this->pages->render('/producto/añadirProducto', ['Producto' => $productoData]);
            }
        } else {
            $_SESSION['ProductoAñadido'] = "failed";
            $this->pages->render('/producto/añadirProducto', ['Producto' => $productoData]);
        }
    
        $productoNuevo->desconecta();
    }

    /**
     * Editar un producto basado en el ID proporcionado.
     */
    public function editarProducto($id)
    {
        if (!isset($id)) {
            $_SESSION['ProductoActualizado'] = "failed";
            $this->pages->render('/producto/productoAdmin');
        } else {
            $producto = Producto::getById($id);
            $this->pages->render('/producto/editarProducto', ['Producto' => $producto]);
        }
    }

    /**
     * Actualizar un producto basado en el envío del formulario.
     */
    public function actualizarProducto()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['producto']) {
                $productoData = $_POST['producto'];

                $producto = new Producto();

                $id = $productoData['id'];
                $nombre = $productoData['nombre'];
                $descripcion = $productoData['descripcion'];
                $categoriaId = $productoData['categoria'];
                $precio = $productoData['precio'];
                $stock = $productoData['stock'];
                $oferta = $productoData['oferta'];
                $fecha = $productoData['fecha'];
                $imagen = $_FILES['imagen']['name'];

                

                $productoNuevo = new Producto();

                $productoNuevo->subirFoto($_FILES['imagen']);
    
                // Validar el producto
                $validacion = $productoNuevo->validarProducto($nombre, $descripcion, $precio, $stock, $oferta);

                if ($validacion['isValid']) {
                    // Sanitizar y establecer los datos del producto
                    $productoSaneado = $producto->sanearProducto($nombre, $descripcion, $categoriaId, $precio, $stock, $oferta, $fecha, $imagen);
                    
                    $producto->setId($id);
                    $producto->setNombre($productoSaneado['nombre']);
                    $producto->setDescripcion($productoSaneado['descripcion']);
                    $producto->setCategoriaId($productoSaneado['categoriaId']);
                    $producto->setPrecio($productoSaneado['precio']);
                    $producto->setStock($productoSaneado['stock']);
                    $producto->setOferta($productoSaneado['oferta']);
                    $producto->setFecha($productoSaneado['fecha']);
                    $producto->setImagen($productoSaneado['imagen']);

                    // Si el producto es válido, actualizarlo en la base de datos
                    $save = $producto->update();

                    if ($save) {
                        $_SESSION['ProductoEditado'] = "complete";
                        $this->gestionarProductos();
                    } else {
                        $_SESSION['ProductoEditado'] = "failed";
                        $this->pages->render('/producto/editarProducto', ['Producto' => $productoData]);
                    }
                } else {
                    // Si el producto no es válido, guardar en la sesión
                    $_SESSION['ProductoEditado'] = "failed";
                    $_SESSION['ErrorProducto'] = $validacion['error'];
                    $_SESSION['Producto'] = $productoData;
                    // Redirigir a la vista de edición de producto
                    $this->pages->render('/producto/editarProducto', ['Producto' => $productoData]);
                }
            } else {
                $_SESSION['ProductoProductoEditadoActualizado'] = "failed";
                $this->pages->render('/producto/editarProducto', ['Producto' => $productoData]);
            }
        } else {
            $_SESSION['ProductoEditado'] = "failed";
            $this->pages->render('/producto/editarProducto', ['Producto' => $productoData]);
        }

        // Desconectarse de la base de datos
        $producto->desconecta();
    }
    
    public static function actualizarStock($producto)
    {
        $id = $producto['id'];
        $stock = $producto['stock'];
        $product = new Producto();
        $product->updateStock($id, $stock);
        $product->desconecta();

        
    }
    /**
     * Borrar un producto basado en el ID proporcionado.
     */
    public function borrarProducto($id)
    {
        if (isset($id)) {
            $producto = new Producto();
            $producto->setId($id);
            $delete = $producto->delete();

            if ($delete) {
                $_SESSION['ProductoBorrado'] = "complete";
            } else {
                $_SESSION['ProductoBorrado'] = "failed";
            }
        } else {
            $_SESSION['ProductoBorrado'] = "failed";
        }

        // Desconectarse de la base de datos
        $producto->desconecta();
        $this->gestionarProductos();
    }




    public function verProducto($id){

        if (isset($id)) {
            $producto = new Producto();
            $producto->setId($id);
            $productoVer = $producto->getById($id);
            $this->pages->render('/producto/verProducto', ['Producto' => $productoVer]);
            $producto->desconecta();
        } else {
            $_SESSION['ProductoBorrado'] = "failed";
        }

    }




    


}
