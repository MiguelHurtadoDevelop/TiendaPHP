<?php
if(isset($_SESSION['login'])&&$_SESSION['login']->rol=='admin'){
    echo "<h1>Gestionar pedidos</h1>";
    echo "<table>";
    echo "<tr><th>ID Pedido</th><th>Usuario ID</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Acciones</th></tr>";
    foreach($pedidos as $pedido){
        echo "<tr>";
        echo "<td>" . $pedido->id . "</td>";
        echo "<td>" . $pedido->usuario_id . "</td>";
        echo "<td>" . $pedido->fecha . "</td>";
        echo "<td>" . $pedido->coste . "</td>";
        echo "<td>" . $pedido->estado . "</td>";
        echo "<td>";
        if ($pedido->estado == 'confirmado') {
            echo "<a href='".BASE_URL."/Pedido/cambiarAEnviado/".$pedido->id."'>Cambiar a enviado</a>";
        } elseif ($pedido->estado == 'enviado') {
            echo "<a href='".BASE_URL."/Pedido/cambiarATerminado/".$pedido->id."'>Terminar</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";

}else{
    echo "No tienes permisos para acceder a esta página";
}