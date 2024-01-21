<?php

use Utils\Utils;

if(isset($_SESSION['login'])&&$_SESSION['login']->rol=='admin'){
 ?>
<?php if(isset($_SESSION['ProductoAñadido']) && $_SESSION['ProductoAñadido'] == 'complete'): ?>
    <strong>Producto añadido correctamente</strong>
<?php elseif(isset($_SESSION['ProductoAñadido']) && $_SESSION['ProductoAñadido'] == 'failed'):?>
    <strong>No se ha podido añadir</strong>
<?php endif;?>

<?php Utils::deleteSession('ProductoAñadido');?>

<?php if(isset($_SESSION['ProductoEditado']) && $_SESSION['ProductoEditado'] == 'complete'): ?>
    <strong>Producto editado correctamente</strong>
<?php elseif(isset($_SESSION['ProductoEditado']) && $_SESSION['ProductoEditado'] == 'failed'):?>
    <strong>No se ha podido editar</strong>
<?php endif;?>
<?php Utils::deleteSession('ProductoEditado');?>


<?php if(isset($_SESSION['ProductoBorrado']) && $_SESSION['ProductoBorrado'] == 'complete'): ?>
    <strong>Producto borrado correctamente</strong>
<?php elseif(isset($_SESSION['ProductoBorrado']) && $_SESSION['ProductoBorrado'] == 'failed'):?>
    <strong>No se ha podido borrar</strong>
<?php endif;?>
<?php Utils::deleteSession('ProductoBorrado');?>

<h1>Gestionar Productos</h1>

<a id="añadir" href="<?=BASE_URL?>/Producto/addProduct/">Añadir Producto</a>
<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripcion</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Oferta</th>
        <th>Fecha</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>

        <?php foreach ($productos as $producto):?>
    <tr>
            <td>
                <?=$producto['id'];?>
            </td>
            <td >
                <?=$producto['nombre'];?>
            </td>
            <td>
                <?=$producto['descripcion'];?>
            </td>
            <td>
                <?=$producto['precio'];?>
            </td>
            <td>
                <?=$producto['stock'];?>
            </td>
            <td>
                <?=$producto['oferta'];?>
            </td>
            <td>
                <?=$producto['fecha'];?>
            </td>
            <td>
                <?=$producto['imagen'];?>
            </td>
            <td>
                <a href="<?=BASE_URL?>/Producto/borrarProducto/<?=($producto['id']);?> ">Borrar</a>
                <a href="<?=BASE_URL?>/Producto/editarProducto/<?=($producto['id']);?> ">Editar</a>
            </td>
    </tr>
        <?php endforeach;?>

</table>


<?php }else{?>
    <h1>No tienes permisos para ver esta página</h1>
<?php }?>