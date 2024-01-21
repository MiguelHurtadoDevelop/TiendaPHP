<?php
// Se utiliza la clase Utils del namespace Utils
use Utils\Utils;

if(isset($_SESSION['login'])&&$_SESSION['login']->rol=='admin'){
    // Verifica si la sesión 'CategoriaActualizada' está seteada y su valor es 'complete'
    if (isset($_SESSION['CategoriaActualizada']) && $_SESSION['CategoriaActualizada'] == 'complete'): ?>
        <strong>Categoria editada correctamente</strong>
    <?php 
    // Verifica si la sesión 'CategoriaActualizada' está seteada y su valor es 'failed'
    elseif (isset($_SESSION['CategoriaActualizada']) && $_SESSION['CategoriaActualizada'] == 'failed'):?>
        <strong>No se ha podido editar la Categoria</strong>
    <?php endif;?>

    <?php
    // Elimina la sesión 'CategoriaActualizada' utilizando el método deleteSession de la clase Utils
    Utils::deleteSession('CategoriaActualizada');?>

    <?php
    // Inicializa la variable $categoria como nula
    $categoria = null;

    // Verifica si la variable $Categoria está seteada
    if (isset($Categoria)):?>
        <?php 
        // Asigna el valor de $Categoria a $categoria
        $categoria = $Categoria;
    ?>
    <?php endif;?>

    <form action="<?= BASE_URL ?>/Categoria/actualizarCategoria/" method="POST">
        <input type="hidden" name="categoria[id]" value="<?=$categoria['id']?>">

        <label for="nombre">Categoria</label>
        <input type="text" value="<?=$categoria['nombre']?>" name="categoria[nombre]" id="nombre" required>
        
        <?php
        // Verifica si la sesión 'ErrorCategoria' está seteada
        if (isset($_SESSION['ErrorCategoria'])):?>
            <strong><?=$_SESSION['ErrorCategoria']?></strong>
        <?php endif;?>

        <?php
        // Elimina la sesión 'ErrorCategoria' utilizando el método deleteSession de la clase Utils
        Utils::deleteSession('ErrorCategoria');?>

        <input type="submit" value="Editar Categoria">
    </form>

<?php }else{
    echo "No tienes permisos para acceder a esta página";
}?>