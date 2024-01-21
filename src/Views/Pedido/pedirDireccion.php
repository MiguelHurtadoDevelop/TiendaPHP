<?php if(isset($_SESSION['login'])){?>

    <h1>Dirección de envio</h1>
    <form method="POST" action="<?= BASE_URL ?>/Pedido/RealizarPedido/">
        <label for="provincia">Provincia:</label>
        <input type="text" id="provincia" name="direccion[provincia]">

        <label for="localidad">Localidad:</label>
        <input type="text" id="localidad" name="direccion[localidad]">

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion[direccion]">

        <input type="submit" value="Enviar">
    </form>

<?php }else{
    echo "No tienes permisos para acceder a esta página";
}?>