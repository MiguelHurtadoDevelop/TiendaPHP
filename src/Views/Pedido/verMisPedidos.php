<?php

echo "<h1>Mis pedidos</h1>";
if(!isset($_SESSION['login'])){
    header('Location: /User/login');
}else{
    if(!isset($pedidos) || empty($pedidos)) {
        echo "No hay pedidos disponibles.";
    } else {
        ?>
        <table>
            <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Dirección</th>
                <th>Provincia</th>
                <th>Localidad</th>
                <th>Coste</th>

            </tr>
            <?php
            foreach($pedidos as $pedido){
                ?>
                <tr>
                    <td><?=$pedido->id?></td>
                    <td><?=$pedido->fecha?></td>
                    <td><?=$pedido->hora?></td>
                    <td><?=$pedido->estado?></td>
                    <td><?=$pedido->direccion?></td>
                    <td><?=$pedido->provincia?></td>
                    <td><?=$pedido->localidad?></td>
                    <td><?=$pedido->coste?>€</td>

                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
}