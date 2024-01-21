<?php use Utils\Utils;?>

<?php 
// Comprueba si el registro se completó correctamente
if(isset($_SESSION['register']) && $_SESSION['register'] == 'complete'): ?>
    <strong>Registro completado correctamente</strong>
<?php 
// Comprueba si el registro falló
elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'):?>
    <strong>No se ha podido registrar</strong>
<?php endif;
// Elimina la variable de sesión 'register' después de mostrar el mensaje
Utils::deleteSession('register');
?>


<form action="<?=BASE_URL?>/User/registro/" method="POST">
    <label for="nombre">Nombre</label>
    <input type="text" value="<?php if(isset($usuario)){echo $usuario['nombre'];}?>" name="data[nombre]" id="nombre" required>

    <?php 
    // Muestra mensajes de error relacionados con el nombre
    if(isset($_SESSION['errorNombre'])):?>
        <strong><?=$_SESSION['errorNombre']?></strong>
    <?php endif;?>
    <?php 
    // Elimina la variable de sesión 'errorNombre' después de mostrar el mensaje
    Utils::deleteSession('errorNombre');
    ?>


    <label for="apellidos">Apellidos</label>
    <input type="text" value="<?php if(isset($usuario)){echo $usuario['apellidos'];}?>" name="data[apellidos]" id="apellidos" required>

    <?php 
    // Muestra mensajes de error relacionados con los apellidos
    if(isset($_SESSION['errorApellidos'])):?>
        <strong><?=$_SESSION['errorApellidos']?></strong>
    <?php endif;?>
    <?php 
    // Elimina la variable de sesión 'errorApellidos' después de mostrar el mensaje
    Utils::deleteSession('errorApellidos');
    ?>

    <label for="email">Email</label>
    <input type="email" value="<?php if(isset($usuario)){echo $usuario['email'];}?>" name="data[email]" id="email" required>

    <?php 
    // Muestra mensajes de error relacionados con el email
    if(isset($_SESSION['errorEmail'])):?>
        <strong><?=$_SESSION['errorEmail']?></strong>
    <?php endif;?>
    <?php 
    // Elimina la variable de sesión 'errorEmail' después de mostrar el mensaje
    Utils::deleteSession('errorEmail');
    ?>

    <label for="password">Contraseña</label>
    <input type="password"  value="<?php if(isset($usuario)){echo $usuario['password'];}?>" name="data[password]" id="password" required>

    <?php 
    // Muestra mensajes de error relacionados con la contraseña
    if(isset($_SESSION['errorPassword'])):?>
        <strong><?=$_SESSION['errorPassword']?></strong>
    <?php endif;?>
    <?php 
    // Elimina la variable de sesión 'errorPassword' después de mostrar el mensaje
    Utils::deleteSession('errorPassword');
    ?>

    <input type="submit" value="Registrarse" required>
</form>

