<?php
// Se utiliza la clase Utils del namespace Utils
use Utils\Utils;

if(isset($_SESSION['login'])&&$_SESSION['login']->rol=='admin'){
    

            // Verifica si la sesión 'CategoriaAñadida' está seteada y su valor es 'complete'
            if (isset($_SESSION['CategoriaAñadida']) && $_SESSION['CategoriaAñadida'] == 'complete'): ?>
                <strong>Categoria añadida correctamente</strong>
            <?php 
            // Verifica si la sesión 'CategoriaAñadida' está seteada y su valor es 'failed'
            elseif (isset($_SESSION['CategoriaAñadida']) && $_SESSION['CategoriaAñadida'] == 'failed'):?>
                <strong>No se ha podido añadir la Categoria</strong>
            <?php endif;?>

            <?php
            // Elimina la sesión 'CategoriaAñadida' utilizando el método deleteSession de la clase Utils
            Utils::deleteSession('CategoriaAñadida');?>

            <?php
            // Inicializa la variable $categoria a null
            $categoria = null;

            // Verifica si la variable $Categoria está seteada
            if (isset($Categoria)):?>
                <?php
                // Asigna el valor de $Categoria a la variable $categoria
                $categoria = $Categoria;?>
            <?php endif;?>

            <form action="<?= BASE_URL ?>/Categoria/AnadirCategoria/" method="POST">
                <label for="nombre">Categoria</label>
                <!-- Muestra el valor de $categoria en el campo de entrada -->
                <input type="text" value="<?php if (isset($categoria)) {echo $categoria;}?>" name="categoria" id="categoria" required>
                
                <?php
                // Verifica si la sesión 'ErrorCategoria' está seteada
                if (isset($_SESSION['ErrorCategoria'])):?>
                    <strong><?=$_SESSION['ErrorCategoria']?></strong>
                <?php endif;?>
                
                <?php
                // Elimina la sesión 'ErrorCategoria' utilizando el método deleteSession de la clase Utils
                Utils::deleteSession('ErrorCategoria');?>
                
                <input type="submit" value="Añadir Categoria" required>
            </form>

<?php }else{
    echo "No tienes permisos para acceder a esta página";
}?>