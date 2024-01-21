<div id="verProducto">
    <div id="verProductoImagen">
        <img src="<?=BASE_URL?>/public/img/<?=$Producto["imagen"]?>">
    </div>
    
    <div id="verProductoTexto">
        <h2>
            <?= $Producto["nombre"]?>
        </h2>
        <p>
            <?= $Producto["descripcion"]?>
        </p>
        <p>
            <?= $Producto["precio"]?>
        </p>
        <form method="POST" action="<?=BASE_URL?>/Carrito/AnadirAlCarrito">
            <input type="hidden" name="producto[id]" value="<?=$Producto['id']?>">
            <input type="number" name="producto[cantidad]" id="cantidad" value="1" min="1" max="<?=$Producto['stock']?>">
            <input type="submit" value="Añadir al carrito">
        </form>
    </div>
    
</div>