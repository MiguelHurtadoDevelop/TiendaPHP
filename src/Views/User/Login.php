<?php
use Utils\Utils;
?>

<?php
// Comprueba si el inicio de sesión se completó correctamente
if(isset($_SESSION['login']) && $_SESSION['login'] == 'complete'): ?>
    <h3>Login completado correctamente</h3>
<?php 
// Comprueba si el inicio de sesión falló
elseif(isset($_SESSION['login']) && $_SESSION['login'] == 'failed'):?>
    <strong>No se ha podido iniciar sesión</strong>
    <?php 
    // Elimina la variable de sesión 'login' después de mostrar el mensaje de error
    Utils::deleteSession('login'); 
?>
<?php endif;?>

<?php
// Comprueba si el registro se completó correctamente
if(isset($_SESSION['register']) && $_SESSION['register'] == 'complete'): ?>
    <strong>Registro completado correctamente, ¿Quieres Iniciar Sesión?</strong>
<?php 
// Comprueba si el registro falló
elseif(isset($_SESSION['register']) && $_SESSION['register'] == 'failed'):?>
    <strong>No se ha podido registrar</strong>
<?php endif;
// Elimina la variable de sesión 'register' después de mostrar el mensaje
Utils::deleteSession('register');
?>

<?php
// Verifica si no hay sesión de inicio o si el inicio de sesión falló
if(!isset($_SESSION['login']) OR $_SESSION['login'] == 'failed'):?>
    <form action="<?=BASE_URL?>/User/login/" method="POST">
        <label for="email">Email</label>
        <input type="email" name="data[email]" id="email" required>

        <?php 
        // Muestra mensajes de error relacionados con el email
        if(isset($_SESSION['errorEmail'])):?>
            <strong><?=$_SESSION['errorEmail']?></strong>
        <?php endif;
        // Elimina la variable de sesión 'errorEmail' después de mostrar el mensaje
        Utils::deleteSession('errorEmail');
        ?>

        <label for="password">Contraseña</label>
        <input type="password" name="data[password]" id="password" required>

        <?php 
        // Muestra mensajes de error relacionados con la contraseña
        if(isset($_SESSION['errorPassword'])):?>
            <strong><?=$_SESSION['errorPassword']?></strong>
        <?php endif;
        // Elimina la variable de sesión 'errorPassword' después de mostrar el mensaje
        Utils::deleteSession('errorPassword');
        ?>

        <input type="submit" value="Login" required>
    </form>
<?php endif;?>
