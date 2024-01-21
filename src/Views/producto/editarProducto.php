<?php
// Se utiliza la clase Utils del namespace Utils
use Utils\Utils;

if(isset($_SESSION['login'])&&$_SESSION['login']->rol=='admin'){
    // Verifica si la sesión 'ProductoAñadido' está seteada y su valor es 'complete'
    if (isset($_SESSION['ProductoAñadido']) && $_SESSION['ProductoAñadido'] == 'complete'): ?>
        <strong>Producto añadido correctamente</strong>
    <?php 
    // Verifica si la sesión 'ProductoAñadido' está seteada y su valor es 'failed'
    elseif (isset($_SESSION['ProductoAñadido']) && $_SESSION['ProductoAñadido'] == 'failed'):?>
        <strong>No se ha podido añadir el Producto</strong>
    <?php endif;?>

    <?php
    // Elimina la sesión 'ProductoAñadido' utilizando el método deleteSession de la clase Utils
    Utils::deleteSession('ProductoAñadido');?>

    <?php
    // Inicializa la variable $producto a null
    $producto = null;

    // Verifica si la variable $Producto está seteada
    if (isset($Producto)):?>
        <?php
        // Asigna el valor de $Producto a la variable $producto
        $producto = $Producto;?>
    <?php endif;?>

    <form action="<?=BASE_URL?>/Producto/actualizarProducto/" method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="producto[id]" value="<?php if (isset($producto['id'])) {echo $producto['id'];} ?>">
        <input type="hidden" name="producto[fecha]" value="<?php if (isset($producto['fecha'])) {echo $producto['fecha'];} ?>">

        <label for="nombre">Nombre</label>
        <input type="text" name="producto[nombre]" id="nombre" required value="<?php if (isset($producto['nombre'])) {echo $producto['nombre'];} ?>">

        <label for="descripcion">Descripcion</label>
        <textarea name="producto[descripcion]" id="descripcion"><?php if (isset($producto['descripcion'])) {echo $producto['descripcion'];} ?></textarea>

        <label for="precio">Precio</label>
        <input type="text" name="producto[precio]" id="precio" required value="<?php if (isset($producto['precio'])) {echo $producto['precio'];} ?>">

        <label for="Categoria">Categoria</label>
        <select id="Categoria" name="producto[categoria]">
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id']; ?>" <?= (isset($producto['categoria']) && $producto['categoria'] == $categoria['id']) ? 'selected' : ''; ?>>
                    <?= $categoria['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="stock">Stock</label>
        <input type="number" name="producto[stock]" id="stock" required value="<?php if (isset($producto['stock'])) {echo $producto['stock'];} ?>">

        <label for="oferta">Oferta</label>
        <input type="number" name="producto[oferta]" id="oferta" required value="<?php if (isset($producto['oferta'])) {echo $producto['oferta'];} ?>">

        <label for="imagen">Imagen</label>
        <input type="file" name="imagen" id="imagen" value="<?php if (isset($producto['imagen'])) {echo $producto['imagen'];} ?>">

        <input type="submit" value="Editar Producto" required>
    </form>

<?php }else{
    echo "No tienes permisos para acceder a esta página";
}?>