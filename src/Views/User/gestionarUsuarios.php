<?php
use Utils\Utils;

if(isset($_SESSION['login'])&&$_SESSION['login']->rol=='admin'){
?>

    <?php
    // Muestra mensajes relacionados con la adición de categorías
    if(isset($_SESSION['CategoriaAñadida']) && $_SESSION['CategoriaAñadida'] == 'complete'): ?>
        <strong>Categoría añadida correctamente</strong>
    <?php elseif(isset($_SESSION['CategoriaAñadida']) && $_SESSION['CategoriaAñadida'] == 'failed'):?>
        <strong>No se ha podido añadir la categoría</strong>
    <?php endif;
    // Elimina la variable de sesión 'CategoriaAñadida'
    Utils::deleteSession('CategoriaAñadida');
    ?>

    <?php
    // Obtiene la lista de usuarios desde el controlador
    $usuarios = \Controllers\UserController::obtenerUsuarios();
    ?>


    <h1>Gestionar Usuarios</h1>

    <a id="añadir" href="<?=BASE_URL?>/User/registrarse/">Añadir nuevo Usuario</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuarios as $usuario):?>
            <tr>
                <td>
                    <?php echo($usuario['id']);?>
                </td>
                <td>
                    <?php echo($usuario['nombre']);?>
                </td>
                <td>
                    <?php echo($usuario['rol']);?>
                </td>
                <td>
                    <!-- Enlace para hacer a un usuario administrador -->
                    <a href="<?=BASE_URL?>/User/hacerAdmin/<?=$usuario['id']?>">Hacer Administrador</a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>

<?php }else{
    echo "No tienes permisos para acceder a esta página";
}?>


