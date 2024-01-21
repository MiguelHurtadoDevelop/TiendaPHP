<div id="productos">
<?php


// Muestra los productos para la página actual
foreach ($productos as $producto): ?>

    <div id="producto">
        <img src="<?=BASE_URL?>/public/img/<?=$producto["imagen"]?>">
        <h2>
            <?= $producto["nombre"]?>
        </h2>
        <p>
            <?= $producto["descripcion"]?>
        </p>
        <p>
            <?= $producto["precio"]?>
        </p>
        <a href="<?=BASE_URL?>/Producto/verProducto/<?=$producto["id"]?>">Ver producto</a>
    </div>

    
<?php endforeach; ?>

</div>