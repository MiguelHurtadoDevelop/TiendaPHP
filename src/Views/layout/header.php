<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>La Tiendita</title>
    <!-- Enlace al archivo de estilos -->
    <link rel="stylesheet" type="text/css" href="<?=BASE_URL?>/public/styles/style.css">
</head>
<body>
<header>
    <h1>La Tiendita</h1>
    <?php 
    // Obtiene las categorías utilizando el controlador de categorías
    $categorias = \Controllers\CategoriaController::obtenerCategorias()?>

    <nav id="menu_cat">
        <ul>
            <li><a href="<?= BASE_URL ?>/Producto/mostrarProductos/">Inicio</a></li>
            <?php 
            // Itera sobre las categorías para mostrarlas en el menú
            foreach ($categorias as $categoria): ?>
                <li>
                    <a href="<?= BASE_URL ?>/Producto/mostrarProductos/<?= $categoria['id'] ?>">
                        <?= $categoria['nombre'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <nav id="menu_user">
        <?php 
        // Verifica si hay una sesión activa y si el inicio de sesión no ha fallado
        if (isset($_SESSION['login']) AND $_SESSION['login']!='failed'):?>
            <!-- Muestra el nombre y apellidos del usuario con un enlace a su perfil -->
            <div id="menuUser">
                <h2><a href="<?=BASE_URL?>/User/editarUsuario/"><?=$_SESSION['login']->nombre?> <?=$_SESSION['login']->apellidos?></a></h2>
                <a href="<?=BASE_URL?>/Carrito/verCarrito/">Carrito</a>
                <a href="<?=BASE_URL?>/Pedido/verMisPedidos/">Mis Pedidos</a>
            </div>
            
        <?php endif;?>
        <div>
            <?php 
            // Verifica si no hay una sesión activa o si el inicio de sesión ha fallado
            if (!isset($_SESSION['login']) OR $_SESSION['login']=='failed'):?>
                <!-- Muestra enlaces para identificarse y registrarse -->
                <a href="<?=BASE_URL?>/User/login/">Identificarse</a>
                <a href="<?=BASE_URL?>/User/registrarse/">Registrarse</a>

            <?php else:?>
                <?php
                // Verifica si el rol del usuario es 'admin'
                if($_SESSION["login"]->rol == "admin"):?>
                    <!-- Muestra enlaces adicionales para gestionar categorías y usuarios si el usuario es administrador -->
                    <a  href="<?=BASE_URL?>/Categoria/gestionarCategoria/">Gestionar Categorias</a>
                    <a  href="<?=BASE_URL?>/Producto/gestionarProductos/">Gestionar Productos</a>
                    <a  href="<?=BASE_URL?>/User/gestionarUsuarios/">Gestionar Usuarios</a>
                    <a  href="<?=BASE_URL?>/Pedido/gestionarPedidos/">Gestionar Pedidos</a>


                <?php endif;?>
                <!-- Muestra un enlace para cerrar sesión -->
                <a href="<?=BASE_URL?>/User/logout/">Cerrar Sesión</a>
            <?php endif;?>
        </div>
    </nav>
</header>
