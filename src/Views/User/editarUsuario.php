<?php
use Utils\Utils;
?>

<?php
// Muestra mensajes relacionados con la actualización de usuarios
if (isset($_SESSION['UsuarioActualizado'])):
    if ($_SESSION['UsuarioActualizado'] == 'complete'): ?>
        <strong>Usuario editado correctamente</strong>
    <?php elseif ($_SESSION['UsuarioActualizado'] == 'failed'): ?>
        <strong>No se ha podido editar el Usuario</strong>
    <?php endif;
    // Elimina la variable de sesión 'UsuarioActualizado'
    Utils::deleteSession('UsuarioActualizado');
endif;
?>

<?php
// Obtiene la información del usuario desde la sesión
$usuario = $_SESSION['login'];
?>

<form action="<?=BASE_URL?>/User/actualizarUsuario/" method="POST">
    <input type="hidden" name="data[id]" value="<?=$usuario->id?>">
    <label for="nombre">Nombre</label>
    <input type="text" value="<?=$usuario->nombre?>" name="data[nombre]" id="nombre" required>

    <?php if(isset($_SESSION['errorNombre'])):?>
        <strong><?=$_SESSION['errorNombre']?></strong>
    <?php endif;?>
    <?php Utils::deleteSession('errorNombre');?>

    <label for="apellidos">Apellidos</label>
    <input type="text" value="<?=$usuario->apellidos?>" name="data[apellidos]" id="apellidos" required>

    <?php if(isset($_SESSION['errorApellidos'])):?>
        <strong><?=$_SESSION['errorApellidos']?></strong>
    <?php endif;?>
    <?php Utils::deleteSession('errorApellidos');?>

    <label for="email">Email</label>
    <input type="email" value="<?=$usuario->email?>" name="data[email]" id="email" required>

    <?php if(isset($_SESSION['errorEmail'])):?>
        <strong><?=$_SESSION['errorEmail']?></strong>
    <?php endif;?>
    <?php Utils::deleteSession('errorEmail');?>

    <!-- Los campos 'password' y 'rol' se mantienen ocultos y no se pueden editar -->

    <input type="hidden" value="<?=$usuario->password?>" name="data[password]" id="password" required>
    <input type="hidden" value="<?=$usuario->rol?>" name="data[rol]" id="rol" required>

    <input type="submit" value="Editar Usuario" required>
</form>
