<?php
use Controllers\ProductoController;
if(isset($_SESSION['login'])){
    if(isset($_SESSION['carrito'])){
        $carrito = $_SESSION['carrito'];
    }else{
        $carrito = [];
    }
    
    if(empty($carrito)){
        echo "<h1>El carrito está vacío</h1>";
    }else{

        echo "<h1>Carrito</h1>";
        $totalCarrito = 0;
        
        foreach ($carrito as $producto) {
            $product=ProductoController::obtenerProducto($producto['id']);
            $precioTotal = $product['precio'] * $producto['cantidad'];
           
            echo "<div id='productoCarrito'>";
                echo "<img src='".BASE_URL."/public/img/".$product['imagen']."'>";
                echo "<h2>".$product['nombre']."</h2>";
                echo "<p>Precio: ".$product['precio']."</p>";

                echo "<div id='unidades' >";
                    echo "<a href='".BASE_URL."/Carrito/restarUnidad/".$product['id']."'>-</a>";
                    echo "<p>".$producto['cantidad']."</p>";
                    echo "<a href='".BASE_URL."/Carrito/anadirUnidad/".$product['id']."'>+</a>";
                echo "</div>";

                echo "<p>Total: ".$precioTotal."</p>";
                $totalCarrito += $precioTotal;
                echo "<a href='".BASE_URL."/Carrito/eliminarDelCarrito/".$product['id']."'>Eliminar del carrito</a>";

            echo "</div>";
            
        }

        echo "<h2>Total Carrito: ".$totalCarrito."</h2>";
        echo "<a id='botonescarrito' href='".BASE_URL."/Carrito/eliminarCarrito/'>Vaciar carrito</a>";
        echo "<a  id='botonescarrito'' href='".BASE_URL."/Pedido/pedirDireccion/'>Realizar pedido</a>";
        
    }
}else{
    echo "<h1>Debes iniciar sesión para ver el carrito</h1>";
}
    
?>




    
